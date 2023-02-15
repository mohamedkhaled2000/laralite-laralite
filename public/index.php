<?php

/**
 * 
 * register autoloading 
 */

 require __DIR__.'/../vendor/autoload.php';

 /**
  * 
  * Bootstrap 
  */

  require __DIR__.'/../Bootstrap/app.php';


  /**
   * 
   *  Run Application
   */


   $app = Application::run();