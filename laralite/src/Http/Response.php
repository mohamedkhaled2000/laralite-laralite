<?php

namespace Laralite\Http;

class Response {

    private function __construct()
    {
        
    }

    /**
     * Return json output
     */

    public static function json($data) {
        return json_encode($data);
    }


    /**
     * 
     * Output 
     */

    public static function output($data) {
        if (! $data) {
            return ;
        }

        if(! is_string($data)) {
            $data = static::json($data);
        }
        echo $data;
    }
}