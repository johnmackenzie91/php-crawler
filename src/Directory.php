<?php

namespace JohnMackenzie91;

use LayerShifter\TLDExtract\Extract;

class Directory
{
    protected $allowedDomains = [];
    protected $urls = [];
    protected $extractor;

    public function __construct()
    {
        $this->extractor = new Extract();
    }

    /**
     * Append to the allowed domains array
     * @param $allowedDomains
     */
    public function addAllowedDomains($allowedDomains)
    {
        if (gettype($allowedDomains) == 'string') {

            $this->allowedDomains[] = $this->extractor->parse($allowedDomains)->getFullHost();
        } elseif (gettype($allowedDomains) === 'array') {

            foreach ($allowedDomains as $domain) {
                $this->allowedDomains[] = $this->extractor->parse($domain)->getFullHost();
            }
        }
    }

    /**
     * Get the allowed domain array
     * @return array
     */
    public function getAllowedDomains()
    {
        return $this->allowedDomains;
    }

    public function addUrl(String $url)
    {
        if(!isset($this->urls[$url])) {
            return $this->urls[$url] = [
                'status_code' => 0,
                'url' => $url,
                'visited' => false,
                'allowed' => $this->isAllowedDomain($url)
            ];
        }
        return $this->urls[$url];

    }

    public function getUrls()
    {
        return $this->urls;
    }

    public function updateUrl(String $url, Array $info)
    {
        if(isset($this->urls[$url])){
            foreach($info as $item => $data) {
                $this->urls[$url][$item] = $data;
            }
            return;
        }
        throw new Exception();
    }

    public function isAllowedDomain($url)
    {
        $domain = $this->extractor->parse($url)->getFullHost();
        return in_array($domain, $this->allowedDomains);
    }

}
