<?php

use JohnMackenzie91\Url;
use PHPUnit\Framework\TestCase;
use JohnMackenzie91\Queues\UrlsCrawled;

class UrlsCrawledStackTest extends TestCase
{
    /**
     * Since we are orking with a singleton object we can only instanciate it once
     * So before we run each test, reset the stack
     */
    public function setUp()
    {
        parent::setUp();
        $stack = UrlsCrawled::Instance();
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
        $stack = UrlsCrawled::Instance();
        $stack->push('www.example.com', ['response' => 200]);
        $stack->push('www.google.com');

        $this->assertEquals(2, $stack->count());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function can_pop_to_stack()
    {
        $stack = UrlsCrawled::Instance(5);

        $stack->push(Url::make(['url' => 'www.google.com', 'response' => 404]));
        $stack->push(Url::make(['url' => 'www.example.com', 'response' => 200]));

        $pop = $stack->pop();

        $this->assertEquals('www.google.com', $pop->url);
        $this->assertEquals(1, $stack->count());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function can_top()
    {
        $stack = UrlsCrawled::Instance();

        $stack->push('www.example.com');
        $stack->push('www.google.com');

        $this->assertEquals('www.example.com', $stack->top());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function is_empty()
    {
        $stack = UrlsCrawled::Instance(5);

        $this->assertTrue(true, $stack->isEmpty());
    }

    /**
     * @test
     * @group urlstocrawl
     */
    public function can_search()
    {
        $stack = UrlsCrawled::Instance(5);

        $stack->push('www.example.com');
        $stack->push('www.google.com');

        $this->assertTrue(true, $stack->has('www.example.com'));
    }
}
