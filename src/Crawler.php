<?php

namespace JohnMackenzie91;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    protected $url;
    protected $links = [];
    protected $maxDepth = 0;
    protected $baseUrl;
    protected $extractor;
    protected $callback;
    protected $directory;

    public function __construct($url, $allowedHosts = [])
    {
        $this->urlHelper = new UrlHelper();
        $this->urlHelper->addAllowedDomains(array_merge($allowedHosts, [$url]));
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
        $this->spider($this->baseUrl, $maxDepth);

        return $this;
    }

    public function links()
    {
        return $this->links;
    }

    private function spider($url, $maxDepth)
    {
        try {

            $this->links[$url] = [
                'status_code' => 0,
                'url' => $url,
                'visited' => false,
                'is_allowed' => true,
            ];

            $request = Request::get($url);


            // Set the status code
            $this->links[$url]['status_code'] = $request['status_code'];
            if ($request['status_code'] == 200) {

                if ($request['response_is_html']) {

                    // collect the links within the page
                    $pageLinks = [];
                    if (@$this->links[$url]['is_allowed'] == true) {
                        $pageLinks = $this->extractLinks($request['response_html'], $url);
                    }

                    // mark current url as visited
                    $this->links[$url]['visited'] = true;

                    $closure = $this->callback;
                    $closure($request['response_html'], $url);

                    // spawn spiders for the child links, marking the depth as decreasing, or send out the soldiers
                    $this->spawn($pageLinks, $maxDepth - 1);
                }
            }
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            // do nothing or something
        } catch (Exception $ex) {
            // call it a 404?
            $this->links[$url]['status_code'] = '404';
        }
    }

    private function spawn($links, $maxDepth)
    {
        // if we hit the max - then its the end of the rope
        if ($maxDepth == 0) {
            return;
        }

        foreach ($links as $url => $info) {
            // only pay attention to those we do not know
            if (!isset($this->links[$url])) {
                $this->links[$url] = $info;
                // we really only care about links which belong to this domain
                if (!empty($url) && !$this->links[$url]['visited'] && $this->links[$url]['is_allowed']) {
                    // restart the process by sending out more soldiers!
                    $this->spider($this->links[$url]['url'], $maxDepth);
                }
            }
        }
    }

    private function extractLinks($html, $url)
    {
        $dom = new DomCrawler($html);
        $currentLinks = [];

        // get the links
        $dom->filter('a')->each(function (DomCrawler $node, $i) use (&$currentLinks) {
            // get the href
            $nodeUrl = $node->attr('href');

            // If we don't have it lets collect it
            if (!isset($this->links[$nodeUrl])) {
                // set the basics
                $currentLinks[$nodeUrl]['is_allowed'] = false;
                $currentLinks[$nodeUrl]['url'] = $nodeUrl;
                $currentLinks[$nodeUrl]['visited'] = false;

                //fix link if not complete
                $currentLinks[$nodeUrl]['url'] = $this->fixUrl($currentLinks[$nodeUrl]['url']);

                $isAllowedHost = $this->urlHelper->isAllowedDomain($currentLinks[$nodeUrl]['url']);

                // check if the link is external
                if ($isAllowedHost) {
                    $currentLinks[$nodeUrl]['is_allowed'] = true;
                }
            }
        });

        // if page is linked to itself, ex. homepage
        if (isset($currentLinks[$url])) {
            // let's avoid endless cycles
            $currentLinks[$url]['visited'] = true;
        }

        // Send back the reports
        return $currentLinks;
    }

    public function fixUrl($url)
    {
        if(substr($url, 0, 1) === '/') {
            return $this->baseUrl . $url;
        }

        return $url;
    }
}
