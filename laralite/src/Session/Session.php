<?php

namespace Laralite\Session;

class Session {

    private function __construct()
    {
        
    }


    /**
     * 
     * Session Start
     */
    public static function start(){

        if(! session_id()) {
            ini_set('session.use_only_cookies',1);
            session_start();
        }
    }

    /**
     * 
     * Set New Session
     * 
     * 
     */

    public static function set($key,$value) {
        $_SESSION[$key] = $value;

        return $value;
    }

    /**
     * Check Session Has Key
     */

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * 
     * Get Session
     */

    public static function get($key) {
        return static::has($key) ? $_SESSION[$key] : null;
    }


    /**
     * 
     * Remove Session
     */

    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    /**
     * 
     * Get All Sessions
     */

    public static function all() {
        return $_SESSION ;
    }

    /**
     * 
     * Destroy session
    */

    public static function destory() {
        foreach (static::all() as $key => $value) {
            static::remove($key);
        }
    }

    /**
     * 
     * Flash Session
     */

    public static function flash($key){
        $value = null;
        if (static::has($key)) {
            $value = static::get($key);
            static::remove($key);
        }

        return $value;
    }
}