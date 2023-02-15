<?php

namespace Laralite\Exception;

class Whoops
{

    private function __construct()
    {
    }


    /**
     * 
     * Handle Error
     */

    public static function handle()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}
