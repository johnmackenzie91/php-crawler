<?php
use PHPUnit\Framework\TestCase;
use Jm\Crawler\LinkParser;


require 'Jm/LinkParser.php';

class LinkParserTest extends TestCase
{
    /**
     * @test
     * @group parser
     */
    public function parses_correct_amount_of_links()
    {
        $html = '<html><head></head><body><a href="/about">About</a><a href="http://example.com">Example</a></body></html>';
        $links = LinkParser::parseLinksFromHtml($html, 'www.test.com');

        $this->assertInternalType('array', $links);
        $this->assertEquals(2, count($links));
    }

    /**
     * @test
     * @group parser
     */
    public function parses_correct_amount_of_links_and_ignores_current_url()
    {
        $html = '<html><head></head><body><a href="/about">About</a><a href="http://example.com">Example</a></body></html>';
        $links = LinkParser::parseLinksFromHtml($html, 'http://example.com');

        $this->assertInternalType('array', $links);
        $this->assertEquals(1, count($links));
    }
}