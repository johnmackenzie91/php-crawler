<?php

namespace JohnMackenzie91\Queues;

class UrlsToCrawlList extends Stack {

    public static function Instance($limit)
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new self($limit);
        }
        return $inst;
    }

    protected function __construct($limit = 0)
    {
        parent::__construct($limit);
    }

    public function has($key)
    {
        return (array_search($key, $this->stack) !== false) ? true : false;
    }
}