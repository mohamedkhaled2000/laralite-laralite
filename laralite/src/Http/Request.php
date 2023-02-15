<?php

namespace Laralite\Http;

class Request {

    private function __construct()
    {
        
    }

    /**
     * 
     * Script name
     */

    private static $script_name;
    /**
     * 
     * Baisic url
     */

    private static $basic_url;

    /**
     * 
     * Url
     */

    private static $url;

    /**
     * Full Url
     */

    private static $full_url;

    /**
     * Query string
     * 
     */

    private static $query_string;

    /**
     * 
     * Handel Rrequest
     */

    public static function handle() {
        static::$script_name = strtolower(str_replace('\\','',dirname(Server::get('SCRIPT_NAME'))));
        static::setBasicUrl();
        static::setUrl();
    }

    /**
     * 
     * Set Basic Url
     */

    private static function setBasicUrl() {
        $protocol = Server::get('REQUEST_SCHEME').'://';
        $host = Server::get('HTTP_HOST');
        $script_name = static::$script_name;

        static::$basic_url = $protocol . $host . $script_name;

    }
    /**
     * 
     * Set Url
     */

     private static function setUrl() {
        $request_uri = urldecode(Server::get('REQUEST_URI'));
        $request_url = preg_replace('#'.static::$script_name.'#','',$request_uri);
        
        static::$full_url = $request_url;

        static::$query_string = '';
        if (strpos($request_url,'?')) {
            list($request_url,$query_string) = explode('?',$request_url);
            static::$query_string = $query_string;
        }

        static::$url = $request_url?:'/';
    }

    /**
     * 
     * Get basic Url
     */

    public static function basic_url() {
        return static::$basic_url;
    }

    /**
     * 
     * Get full Url
     */

    public static function full_url() {
        return static::$full_url;
    }

    /**
     * 
     * Get Url
     */

    public static function url() {
        return static::$url;
    }

    /**
     * 
     * Get query string
     */

    public static function query_string() {
        return static::$query_string;
    }

    /**
     * 
     * Get method
     */

    public static function method() {
        return Server::get('REQUEST_METHOD');
    }

    /**
     * 
     * check 
     */

    public static function has($type,$key) {
        return array_key_exists($key,$type);
    }

    /**
     * 
     * Value of types
     */

    public static function value($key,$type = null) {
        $type = isset($type) ? $type : $_REQUEST;
        return static::has($type,$key) ? $type[$key] : null;
    }


    /**
     * 
     * Get param method Get
     */

    public static function get($key) {
        return static::value($key,$_GET);
    }

    /**
     * 
     * Get param method Post
     */

    public static function post($key) {
        return static::value($key,$_POST);
    }

    /**
     * 
     * Set value to request
     */

    public static function set($key,$value) {
        $_REQUEST[$key] = $value;
        $_GET[$key] = $value;
        $_POST[$key] = $value;

        return $value;
    }

    /**
     * 
     * Previous request
     */

    public static function previous() {
        return Server::get('HTTP_PEFERER');
    }


    /**
     * 
     * Previous request
     */

     public static function all() {
        return $_REQUEST;
    }


}