<?php

use PHPUnit\Framework\TestCase;
use JohnMackenzie91\Directory;

class DirectoryTest extends TestCase
{
    /**
     * @test
     * @filter directory
     */
    public function can_add_new_allowed_domain()
    {
        $directory = new Directory();
        $directory->addAllowedDomains('johnmackenzie.co.uk');

        $this->assertEquals(['johnmackenzie.co.uk'], $directory->getAllowedDomains());

        $directory->addAllowedDomains(['google.com', 'yahoo.co.uk']);

        $this->assertEquals(['johnmackenzie.co.uk', 'google.com', 'yahoo.co.uk'], $directory->getAllowedDomains());
    }

    /**
     * @test
     */
    public function can_add_allowed_url()
    {
        $directory = new Directory();
        $directory->addAllowedDomains('www.johnmackenzie.co.uk');

        // insert an allowed/unvisited domain
        $url = $directory->addUrl('https://www.johnmackenzie.co.uk/blog');

        // url should be added to index
        $this->assertEquals(1, count($directory->getUrls()));

        // visited before should be false
        $this->assertEquals(false, $url['visited']);

        //allowed to visit should be true
        $this->assertEquals(true, $url['allowed']);

    }

    /**
     * @test
     */
    public function can_add_disallowed_url()
    {
        $directory = new Directory();
        $directory->addAllowedDomains('www.johnmackenzie.co.uk');

        // insert an allowed/unvisited domain
        $url = $directory->addUrl('https://subdomain.johnmackenzie.co.uk/something-else');

        //allowed to visit should be true
        $this->assertEquals(false, $url['allowed']);

    }

    /**
     * @test
     */
    public function is_allowed_host()
    {
        $directory = new Directory();
        $directory->addAllowedDomains('www.johnmackenzie.co.uk');

        $this->assertEquals(true, $directory->isAllowedDomain('https://www.johnmackenzie.co.uk/blog'));
    }


}
?>