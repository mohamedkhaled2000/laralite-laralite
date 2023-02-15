<?php

namespace Laralite\File;

class File {
    
    private function __construct()
    {
    }

    /**
     * 
     * Root path
     */

    public static function root() {
        return ROOT;
    }

    /**
     * 
     * DS path
     */

    public static function ds() {
        return DS;
    }

    /**
     * 
     * Get file full path
     */

    public static function path($path) {
        $path = static::root() . static::ds() . trim($path,'/');
        $path = str_replace(['/','\\'],static::ds(),$path);
        return $path;
    }

    /**
     * 
     * check file exits
     */

    public static function exists($path) {
        return file_exists(static::path($path));
    }

    /**
     * 
     * required file exits
     */

    public static function require_file($path) {
        if (static::exists($path)) {
            return require_once static::path($path);
        }
    }

    /**
     * 
     * required file exits
     */

     public static function include_file($path) {
        if (static::exists($path)) {
            return include static::path($path);
        }
    }

    /**
     * 
     * required directory
     */

     public static function require_directory($path) {
        $files = array_diff(scandir(static::path($path)),['.','..']);
        foreach ($files as $file) {
            $file_path = $path . static::ds() . $file;
            if (static::exists($file_path)) {
                static::require_file($file_path);
            }
        }
    }
}