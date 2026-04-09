<?php
require "app/app.php";
use App\Controller\Api\JsonResApi;
use App\Core\Router;
use App\Core\Env;
use App\Middleware\ApiMiddleware;
use App\Middleware\AuthMiddleware;

// Get URL
$url = $_GET['url'] ?? 'logIn';
$method = $_SERVER['REQUEST_METHOD'];

// Load routes
require_once 'routes.php';
require_once 'api.php';
Env::load(BASE_PATH . '.env');

// RESOLVE ROUTE
$routeKey = Router::resolve($method, $url);

// Route not found
if (!$routeKey) {
    JsonResApi::Response([
        "status" => "error",
        "msg" => "Route Not Found"
    ],404);
    exit;
}

// Extract route data
[$route, $params] = $routeKey;

// Middleware Map
function resolveMiddleware($name){
    $map = [
        'auth' => AuthMiddleware::class, 
        'api' => ApiMiddleware::class
    ];

    if (!isset($map[$name])) {
        throw new Exception("Middleware '$name' not found");
    }

    return $map[$name];
}

// Run middlewares
foreach ($route['middleware'] as $middleware) {
    $middlewareClass = resolveMiddleware($middleware);
    (new $middlewareClass)->handle();
}


$controllerName = $route['controller'];
$methodName = $route['method'];

// Check if controller exists
if (!class_exists($controllerName)) {
    jsonResApi::Response([
        "status" => "error",
        "msg" => "Controller '$controllerName' not found"
    ],500);
    exit;
}

// Create controller instance
$controller = new $controllerName();

// Check if method exists
if (!method_exists($controller, $methodName)) {
    header("Content-Type: application/json");
    jsonResApi::Response([
        "status" => "error",
        "msg" => "Method '$methodName' not found in $controllerName"
    ],500);
    exit;
}


call_user_func_array([$controller, $methodName], $params);
