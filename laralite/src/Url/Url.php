<?php

namespace Laralite\Url;

use Laralite\Http\Request;
use Laralite\Http\Server;

class Url {

    private function __construct()
    {
        
    }

    /**
     * get path url
     */

    public static function path($path) {
        return Request::basic_url() . '/' . trim($path,'/');
    }

    /**
     * get previous path url
     */

    public static function previous() {
        return Server::get('REQUEST_REFERER');
    }


    /**
     * get previous path url
     */

    public static function redirect($path) {
        header('location: ' . $path);
        exit();
    }

}