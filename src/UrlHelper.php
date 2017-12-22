<?php

namespace JohnMackenzie91;

use LayerShifter\TLDExtract\Extract;

class UrlHelper
{
    private $allowedDomains = [];
    private $extract;

    public function __construct()
    {
        $this->extract = $extract = new Extract();
    }

    public function addAllowedDomains($domains)
    {
        if (gettype($domains) === 'string') {
            array_push($this->allowedDomains, $domains);
        } elseif (getType($domains) === 'array') {
            foreach ($domains as $url) {
                array_push($this->allowedDomains, $this->getDomain($url));
            }
        }
    }

    public function getAllowedDomains()
    {
        return $this->allowedDomains;
    }

    public function isAllowedDomain($url)
    {
        return in_array($this->getDomain($url), $this->allowedDomains);
    }

    public function getDomain($url)
    {
        $result = $this->extract->parse($url);
        return $result->getFullHost();
    }
}
