<?php
use App\Core\Router;

Router::group('', ['controller' => App\Controller\User::class], function(){
    Router::get('login', 'login');
    Router::get('register', 'register');
});