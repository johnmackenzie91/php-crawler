<?php

use PHPUnit\Framework\TestCase;
use JohnMackenzie91\UrlHelper;

class UrlHelperTest extends TestCase
{
    /**
     * @test
     * @filter urlhelper
     */
    public function can_add_new_allowed_domain()
    {
        $urlHelper = new UrlHelper();
        $urlHelper->addAllowedDomains(['johnmackenzie.co.uk']);

        $this->assertEquals(['johnmackenzie.co.uk'], $urlHelper->getAllowedDomains());

        $urlHelper->addAllowedDomains(['google.com']);

        $this->assertEquals(['johnmackenzie.co.uk', 'google.com'], $urlHelper->getAllowedDomains());

        $urlHelper->addAllowedDomains(['https://yahoo.co.uk']);

        $this->assertEquals(['johnmackenzie.co.uk', 'google.com', 'yahoo.co.uk'], $urlHelper->getAllowedDomains());
    }

    /**
     * @test
     * @filter urlhelper
     */
    public function is_allowed_domain()
    {
        $directory = new UrlHelper();
        $directory->addAllowedDomains(['www.johnmackenzie.co.uk']);

        $this->assertEquals(true, $directory->isAllowedDomain('https://www.johnmackenzie.co.uk/blog'));
    }


}
?>