<?php

namespace Laralite\Bootstrap;

use Laralite\Exception\Whoops;
use Laralite\File\File;
use Laralite\Http\Request;
use Laralite\Http\Response;
use Laralite\Http\Server;
use Laralite\Router\Route;
use Laralite\Session\Session;

class App {

    private function __construct() { }


    /**
     * 
     * Run Method
     */

    public static function run(){

        // Whoops Init
        Whoops::handle();
        
        // Start Session 
        Session::start();

        // Request handle
        Request::handle();

        // Requires All Routes
        File::require_directory('/routes');

        // Handle Routes
       $data = Route::handle();

       Response::output($data);
        // echo '<pre>';
        // print_r(Route::handle());
        // echo '<pre>';
    }
}