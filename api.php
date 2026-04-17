<?php
use App\Core\Router;

Router::group('', ['controller' => App\Http\Controller\Api\AuthApi::class], function () {
    Router::post('register', 'register');
    Router::post('login', 'login');
});