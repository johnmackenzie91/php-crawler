<?php

use PHPUnit\Framework\TestCase;
use JohnMackenzie91\Queues\UrlsToCrawlList;

class UrlsToCrawlStackTest extends TestCase
{
    /**
     * Since we are orking with a singleton object we can only instanciate it once
     * So before we run each test, reset the stack
     */
    public function setUp()
    {
        parent::setUp();
        $stack = UrlsToCrawlList::Instance(5);
        while ($stack->count() !== 0) {
            $stack->pop();
        }
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function can_push_to_stack()
    {
        $stack = UrlsToCrawlList::Instance(5);

        $stack->push('www.example.com');
        $stack->push('www.google.com');

        $this->assertEquals(2, $stack->count());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function can_pop_to_stack()
    {
        $stack = UrlsToCrawlList::Instance(5);

        $stack->push('www.example.com');
        $stack->push('www.google.com');

        $pop = $stack->pop();

        $this->assertEquals('www.google.com', $pop);
        $this->assertEquals(1, $stack->count());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function can_top()
    {
        $stack = UrlsToCrawlList::Instance(5);

        $stack->push('www.example.com');
        $stack->push('www.google.com');

        $this->assertEquals('www.google.com', $stack->top());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function is_empty()
    {
        $stack = UrlsToCrawlList::Instance(5);

        $this->assertTrue(true, $stack->isEmpty());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function can_search()
    {
        $stack = UrlsToCrawlList::Instance(5);

        $stack->push('www.example.com');
        $stack->push('www.google.com');

        $this->assertTrue(true, $stack->has('www.example.com'));
    }
}
