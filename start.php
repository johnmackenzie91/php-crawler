<?php

require './vendor/autoload.php';
require 'src/Crawler.php';


echo "A simple web crawler" . PHP_EOL;

use JohnMackenzie91\Crawler as Crawler;

$crawler = new Crawler("https://www.johnmackenzie.co.uk", []);

$dom = $crawler->crawl(10, function (){
    echo 'aaa';
});
