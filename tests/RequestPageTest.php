<?php

use Jm\Crawler\RequestPage;
use PHPUnit\Framework\TestCase;
use Jm\Crawler\LinkParser;


require 'Jm/RequestPage.php';

class RequestPageTest extends TestCase
{
    /**
     * @test
     * @group request
     */
    public function get_me_google()
    {
        $request = RequestPage::get("http://www.google.com");

        $this->assertEquals(200, $request['response']);
        $this->assertInternalType('string', $request['body']);
    }
}