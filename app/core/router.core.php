<?php
namespace App\Core;

class Router{
    //intial state of routes,prefix,controller and middleware
    private static $routes = [];
    private static $currentPrefix = "";
    private static $currentController = '';
    private static $currentMiddleware = [];

    public static function group($prefix, $options, $callBack){
        //to get the previous state of the group
        $prevPrefix = self::$currentPrefix;
        $prevController = self::$currentController;
        $prevMiddleware = self::$currentMiddleware;

        //set new state of the group
        self::$currentPrefix = trim($prevPrefix . '/' . $prefix, '/');
        self::$currentController = $options['controller'] ?? $prevController;

        //Handle middleware
        if (isset($options['middleware'])) {
            $newMiddleware = is_array($options['middleware']) 
                ? $options['middleware'] 
                : [$options['middleware']];
            
            self::$currentMiddleware = array_merge($prevMiddleware, $newMiddleware); //[...$prevMiddleware, $newMiddleware]
        }

        //our callback function
        $callBack();

        //restored to old state
        self::$currentPrefix = $prevPrefix;
        self::$currentController = $prevController;
        self::$currentMiddleware = $prevMiddleware;
    }


    //HTTP ROUTES
    //GET
    public static function get($path, $handler, $middleware = []) { 
        self::addRoute('GET', $path, $handler, $middleware); 
    }

    //POST
    public static function post($path, $handler, $middleware = []) {
         self::addRoute('POST', $path, $handler, $middleware); 
    }

    //PUT(edit)
    public static function put($path, $handler, $middleware = []) { 
        self::addRoute('PUT', $path, $handler, $middleware); 
    }

    //DELETE
    public static function delete($path, $handler, $middleware = []) {
         self::addRoute('DELETE', $path, $handler, $middleware); 
    }

    //ANY
    public static function any($path, $handler, $middleware = []) { 
        self::addRoute('ANY', $path, $handler, $middleware); 
    }

    //Add route
    private static function addRoute($method, $path, $handler, $middleware){
        $fullPath = trim(self::$currentPrefix . '/' . trim($path, '/'), '/');

        // Controller + method
        if (is_string($handler)) {
            $controller = self::$currentController;
            $methodName = $handler;
        } else{
            [$controller, $methodName] = $handler;
        }

        // Merge middleware
        $routeMiddleware = array_merge(
            self::$currentMiddleware,
            \is_array($middleware) ? $middleware : [$middleware]
        );

        self::$routes[$method][$fullPath] = [
            'controller' => $controller,
            'method' => $methodName,
            'middleware' => $routeMiddleware
        ];
    }

    //resolved routes
    public static function resolve($method, $uri){
        $uri = trim($uri, '/');
        $segments = explode('/', $uri);

        $routes = self::$routes[$method] ?? [];

        // 1. Exact match
        if (isset($routes[$uri])) {
            return [$routes[$uri], []];
        }

        // 2. Dynamic match
        foreach ($routes as $pattern => $route) {
            $patternSegments = explode('/', $pattern);

            if (\count($patternSegments) !== \count($segments)) {
                continue;
            }

            $params = [];
            $matched = true;

            foreach ($patternSegments as $i => $part) {
                if (preg_match('/^\{.+\}$/', $part)) {
                    $params[] = $segments[$i];
                } elseif ($part !== $segments[$i]) {
                    $matched = false;
                    break;
                }
            }

            if ($matched) {
                return [$route, $params];
            }
        }

        // 3. Fallback
        for ($i = count($segments); $i > 0; $i--) {
            $test = implode('/', array_slice($segments, 0, $i));

            if (isset($routes[$test])) {
                return [$routes[$test], array_slice($segments, $i)];
            }
        }

        return null;
    }
}