<?php
/**
 * Created by IntelliJ IDEA.
 * User: johnmackenzie
 * Date: 22/12/2017
 * Time: 12:33
 */

namespace JohnMackenzie91;

class Url
{
    public $url;
    public $status_code;
    public $response_html;
    public $content_type;
    public $response_is_html;

    public static function make($data)
    {
        $url = new self;
        foreach ($data as $key => $value) {
            $url->{$key} = $value;
        }
        return $url;
    }
}
