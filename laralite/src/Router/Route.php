<?php

namespace Laralite\Router;

use Laralite\Http\Request;
use Laralite\View\View;

class Route {

    private function __construct()
    {
        
    }

   /**
    * Route Array
    */

    private static $routes = [];

    /**
     * Middleware
     */

     private static $middleware;

     /**
      * prefix
      */
    private static $prefix;

    /**
     * 
     * Add route
     */

    private static function add($methods,$url,$callback) {
        $uri = rtrim(static::$prefix . '/' . trim($url,'/'),'/');
        $uri = $uri?:'/';
        foreach (explode('|',$methods) as $method) {
            static::$routes[] = [
                'url'           => $uri,
                'callback'      => $callback,
                'method'        => $method,
                'middleware'    => static::$middleware,
            ];
        }
    }

    /**
     * 
     * Add new get route
     */

    public static function get($url,$callback) {
        static::add('GET',$url,$callback);
    }

    /**
     * 
     * Add new post route
     */

    public static function post($url,$callback) {
        static::add('POST',$url,$callback);
    }

    /**
     * 
     * Add new any route
     */

    public static function any($url,$callback) {
        static::add('GET|POST',$url,$callback);
    }

    /**
     * set prefix
     */

    public static function prefix($prefix,$callback) {
        $perant_prefix = static::$prefix;
        static::$prefix .= '/' . trim($prefix,'/');
        if (is_callable($callback)) {
            call_user_func($callback);
        }else {
            throw new \Exception('Please Provide callback function');
        }

        static::$prefix = $perant_prefix;
    }

    /**
     * set middleware
     */

    public static function middleware($middleware,$callback) {
        $perant_middleware = static::$middleware;
        static::$middleware .= '|' . trim($middleware,'|');
        if (is_callable($callback)) {
            call_user_func($callback);
        }else {
            throw new \Exception('Please Provide callback function');
        }

        static::$middleware = $perant_middleware;
    }

    /**
     * 
     * handle route
     */

    public static function handle() {
        $matched = true;
        $uri = Request::url();

        foreach (static::$routes as $route) {
            $route['url'] = preg_replace('/\/{(.*?)}/','/(.*?)',$route['url']);
            $route['url'] = '#^' . $route['url'] . '$#';

            if(preg_match($route['url'],$uri,$matches)) {
                array_shift($matches);
                $params = array_values($matches);
                
                foreach ($params as $param) {
                    if (strpos($param,'/')) {
                        $matched = false; 
                    }
                }

                if($route['method'] != Request::method()) {
                    $matched = false;
                }

                if($matched) {
                    return static::invoke($route,$params);
                }
            }

        }

        return View::render('errors.404');

    }

    /**
     * 
     * Do callback function
     */

    public static function invoke($route , $params = []) {
        static::excuteMiddleware($route);
        $callback = $route['callback'];

        if(is_callable($callback)) {
            return call_user_func_array($callback,$params);
        } elseif (is_array($callback)) {
            list($controller,$method) = $callback;

            if(class_exists($controller)){
                $object = new $controller;

                if (method_exists($object,$method)) {
                    return call_user_func_array([$object,$method],$params);
                } else {
                    throw new \BadFunctionCallException("The Method $method is not exsists at $controller");
                }

            } else {
                throw new \Exception("Class $controller not founded");
            }

        } elseif (strpos($callback,'@')) {
            list($controller,$method) = explode('@',$callback);
            $controller = 'App\Controllers\\' . $controller;

            if(class_exists($controller)){
                $object = new $controller;

                if (method_exists($object,$method)) {
                    return call_user_func_array([$object,$method],$params);
                } else {
                    throw new \BadFunctionCallException("The Method $method is not exsists at $controller");
                }

            } else {
                throw new \Exception('Class not founded');
            }
        } else {
            throw new \InvalidArgumentException('Please provide callback function');
        }
    }


    /**
     * 
     * check middleware
     */

    public static function excuteMiddleware($route) {
        foreach (explode('|',$route['middleware']) as $middleware) {
            if ($middleware != '') {
                $middleware = 'App\Middleware\\' . $middleware;
                if (class_exists($middleware)) {
                    $object = new $middleware;
                     call_user_func_array([$object,'handle'],[]);
                } else {
                    throw new \Exception("Class $middleware not founded");
                }
            }
        }
    }

}