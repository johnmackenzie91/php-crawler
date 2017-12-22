<?php

namespace JohnMackenzie91\Queues;

class Stack
{
    protected $stack;
    protected $limit;

    protected function __construct($limit = 10)
    {
        // initialize the stack property
    $this->stack = array();
    // stack can only contain this many items
    $this->limit = $limit;
    }

    /**
     * Push an item to the stack
     * @param $item
     * @return bool
     */
    public function push($item)
    {

        // trap for stack overflow
        if ($this->limit === 0 || count($this->stack) < $this->limit) {
            // prepend item to the start of the array
            array_unshift($this->stack, $item);
        } else {
            return false;
        }
    }

    /**
     * Remove the last item from the stack
     * @return bool
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            // trap for stack underflow
            return false;
        } else {
            // pop item from the start of the array
            return array_shift($this->stack);
        }
    }

    /**
     * Return the top item but keep it in the stack
     * @return mixed
     */
    public function top()
    {
        return current($this->stack);
    }

    /**
     * Check whether the stackis empty
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->stack);
    }

    public function count()
    {
        return count($this->stack);
    }
}
