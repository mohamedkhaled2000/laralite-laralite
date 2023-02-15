<?php

namespace Laralite\Http;

class Server {

    private function __construct()
    {
        
    }

    /**
     * 
     * Check Key On server
     */

    public static function has($key) {
        return isset($_SERVER[$key]);
    }

    /**
     * Get value by key
     */

    public static function get($key) {
        return static::has($key) ? $_SERVER[$key] : null;
    }

    /**
     * 
     * Get Path info
     */

    public static function path_info($path) {
        return pathinfo($path) ;
    }

    /**
     * 
     * Get All Servers
     */

    public static function all() {
        return $_SERVER;
    }
}