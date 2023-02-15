<?php

namespace Laralite\Cookie;

class Cookie {

    private function __construct()
    {
        
    }


    /**
     * 
     * Set New Cookie
     * 
     * 
     */

    public static function set($key,$value) {
        $expire = time() * (1 * 365 * 24 * 60 * 60);
        setcookie($key,$value,$expire,'/','',false,true);

        return $value;
    }

    /**
     * Check Cookie Has Key
     */

    public static function has($key) {
        return isset($_COOKIE[$key]);
    }

    /**
     * 
     * Get Cookie
     */

    public static function get($key) {
        return static::has($key) ? $_COOKIE[$key] : null;
    }


    /**
     * 
     * Remove Cookie
     */

    public static function remove($key) {
        unset($_COOKIE[$key]);
        setcookie($key,null,'-1','/');
    }

    /**
     * 
     * Get All Cookie
     */

    public static function all() {
        return $_COOKIE ;
    }

    /**
     * 
     * Destroy Cookie
    */

    public static function destory() {
        foreach (static::all() as $key => $value) {
            static::remove($key);
        }
    }

}