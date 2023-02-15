<?php

namespace Laralite\View;
use Jenssegers\Blade\Blade;

use Laralite\File\File;
use Laralite\Session\Session;

class View {

    private function __construct()
    {
        
    }

    /**
     * 
     * Render blade views
     */

    public static function bladeRender($path,$data = []) {
        $blade = new Blade(File::path('views'), File::path('storage/cache') );
        return $blade->make($path, $data)->render();
        
    }

    /**
     * 
     * Render view
     */

    public static function render($path,$data = []) {

        /**
         * if you want render from .php files
         */

        // $path = 'views' . File::ds() . str_replace('.',File::ds(),$path) . '.php';
        // if (! File::exists($path)) {
        //     throw new \Exception("The view file $path Not Found");
        // }

        // ob_start();
        // extract($data);
        // include File::path($path);
        // $content = ob_get_contents();
        // ob_end_clean();
        // return $content;

        ////////////////////////////////////////////////////////////////////////////////

        /**
         * if you want render from .blade files
         */
        $errors = Session::flash('errors');
        $old = Session::flash('old');
        $data = array_merge($data, ['errors' => $errors,'old' => $old]);

        return static::bladeRender($path,$data);


    }
}