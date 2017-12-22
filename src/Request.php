<?php

namespace JohnMackenzie91;

use GuzzleHttp\Client;

class Request
{
    public static function get($url)
    {
        $html = $contentType = false;
        try {
            // Create a client and send out a request to a url
            $client = new Client();
            $request = $client->request('GET', $url);

            // get the content of the request result
            $html = $request->getBody()->getContents();
            // lets also get the status code
            $statusCode = $request->getStatusCode();
            $contentType = $request->getHeader('Content-Type')[0];
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            // do nothing or something
            $statusCode = 500;
        } catch (Exception $ex) {
            // call it a 404?
            $statusCode = 404;
        }

        return [
            'response_html' => $html,
            'status_code' => $statusCode,
            'content_type' => $contentType,
            'response_is_html' => (strpos($contentType, 'text/html') !== false) ? true : false
        ];
    }
}
