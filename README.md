# php-crawler
A web crawler built in PHP

## Getting Started
````php
require './vendor/autoload.php';
use JohnMackenzie91\Crawler as Crawler;
````

Instanciat a new Crawler Object, this takes the start domain, and an array of allowed domains, the domain of the start url is automatically added to the list of allowed domains.

````php
$crawler = new Crawler("https://www.johnmackenzie.co.uk", ["johnmackenzie.co.uk"]);
````

Then begin the crawl by calling crawl() on the Crawler object
This method allows you to pass a callback which will be run once a successfuly request has been made to the url
The variables passed into this call back is the response from the request, and the url. 

```php
$dom = $crawler->crawl(10, function ($html, $url){
    echo 'Successfully scanned ' . $url . PHP_EOL;
    // do something cool with the $html
});
```