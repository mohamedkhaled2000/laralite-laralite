<?php

use Laralite\Bootstrap\App;

class Application {

    /**
     * 
     *  Private Constractor
     */

    private function __construct() { }


    /**
     * 
     *  Application Run Function
     */

    public static function run(){
        
        /**
         * 
         * Define Root path
         */

        define('ROOT',realpath(__DIR__.'/..'));

        /**
         * 
         * Define Directrory Seperator
         */

        define('DS',DIRECTORY_SEPARATOR);

        /**
         * Run App
        */

        App::run();
    }
}