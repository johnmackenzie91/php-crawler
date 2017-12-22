<?php

namespace JohnMackenzie91;

use GuzzleHttp\Client;
use JohnMackenzie91\Queues\UrlsCrawled;
use JohnMackenzie91\Queues\UrlsToCrawl;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    protected $url;
    protected $maxDepth = 0;
    protected $baseUrl;
    protected $callback;
    protected $urlsCrawled;
    protected $urlsToCrawl;

    public function __construct($url, $allowedHosts = [])
    {
        $this->urlHelper = new UrlHelper();
        $this->urlHelper->addAllowedDomains(array_merge($allowedHosts, [$url]));
        $this->urlsToCrawl = UrlsToCrawl::Instance();
        $this->urlsCrawled = UrlsCrawled::Instance();
        $this->baseUrl = $url;
    }

    /**
     * Begin crawling site
     * @param int $maxDepth
     * @param $callback
     * @return $this
     */
    public function crawl($maxDepth = 10, $callback)
    {
        $this->callback = $callback;
        $this->depth = $maxDepth;

        // Add start url to stack
        $this->urlsToCrawl->push(Url::make(['url' => $this->baseUrl]));

        // while the stack is not empty pop off top and crawl!
        while ($this->urlsToCrawl->isEmpty() === false) {
            //Grab first element
            $url = $this->urlsToCrawl->pop();
            //Crawl First Element
            $result = $this->spider($url, $maxDepth);

            $this->urlsCrawled->push($result);
        }

        return $this;
    }

    public function links()
    {
        return $this->links;
    }

    private function spider($url, $maxDepth)
    {
        try {
            $response = Url::make(Request::get($url->url));

            if ($response->status_code == 200) {
                if ($response->response_is_html) {

                    // collect the links within the page
                    $pageLinks = $this->extractLinks($response->response_html, $url);

                    foreach ($pageLinks as $linkFound) {
                        $foundLink = Url::make(['url' => $linkFound]);

                        //is allowed domain?
                        if ($this->urlHelper->isAllowedDomain($foundLink->url) === false) {
                            continue;
                        //do we already have it on our list to crawl?
                        } elseif ($this->urlsToCrawl->has($foundLink)) {
                            continue;
                        //have we already crawled it?
                        } elseif ($this->urlsCrawled->has($foundLink)) {
                            continue;
                        } else {
                            // All is good, add it to Stack
                            $this->urlsToCrawl->push($foundLink);
                        }
                    }

                    //Call the callback
                    $closure = $this->callback;
                    $closure($response->response_html, $url->url);
                }
            }
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            // do nothing or something
            $response->status_code = '500';
            $response->response_html = null;
            $response->content_type = null;
            $response->response_is_html = null;
        } catch (Exception $ex) {
            // call it a 404?
            $response->status_code = '404';
            $response->response_html = null;
            $response->content_type = null;
            $response->response_is_html = null;
        }
        return $response;
    }

    private function extractLinks($html, $url)
    {
        $dom = new DomCrawler($html);
        $pageLinks = [];

        // get the links
        $dom->filter('a')->each(function (DomCrawler $node, $i) use (&$pageLinks) {
            // get the href
            $nodeUrl = $node->attr('href');
            array_push($pageLinks, $nodeUrl);
        });
        // Send back the reports
        return $pageLinks;
    }

    public function fixUrl($url)
    {
        if (substr($url, 0, 1) === '/') {
            return $this->baseUrl . $url;
        }

        return $url;
    }
}
