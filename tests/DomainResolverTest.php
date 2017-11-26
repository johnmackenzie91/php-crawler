<?php
use PHPUnit\Framework\TestCase;
use Jm\Crawler\Crawler;


require 'Jm/crawler.php';

class DomainResolverTest extends TestCase
{
    /**
     * @test
     */
    public function detects_unwanted_host()
    {
        $startUrl = 'https://www.johnmackenzie.co.uk';
        $allowedHosts = ['www.johnmackenzie.co.uk'];

        $crawler = new Crawler($startUrl, $allowedHosts);

        // check that url is not allowed
        $unwantedUrl = 'example.com';
        $result = $crawler->allowedHost($unwantedUrl);

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function detects_wanted_host()
    {
        $startUrl = 'https://www.johnmackenzie.co.uk';
        $allowedHosts = ['www.johnmackenzie.co.uk'];

        $crawler = new Crawler($startUrl, $allowedHosts);

        // check that url IS allowed
        $wantedUrl = 'www.johnmackenzie.co.uk';
        $result = $crawler->allowedHost($wantedUrl);

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function relative_link_is_mapped_to_this_domain()
    {
        $startUrl = 'https://www.johnmackenzie.co.uk';
        $allowedHosts = ['www.johnmackenzie.co.uk'];

        $crawler = new Crawler($startUrl, $allowedHosts);

        // check that url is not allowed
        $relativeUrl = '/about';
        $result = $crawler->checkIfAllowed($relativeUrl);

        $this->assertEquals('https://www.johnmackenzie.co.uk/about', $result);
    }

    /**
     * @test
     */
    public function should_not_be_allowed()
    {
        $startUrl = 'https://www.johnmackenzie.co.uk';
        $allowedHosts = ['www.johnmackenzie.co.uk'];

        $crawler = new Crawler($startUrl, $allowedHosts);

        // check that url is not allowed
        $githubUrl = 'github.com';
        $result = $crawler->checkIfAllowed($githubUrl);

        $this->assertFalse($result);
    }
}
?>