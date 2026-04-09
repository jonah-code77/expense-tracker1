<?php
use App\Core\Router;

//Public Routes
Router::group('', ['controller' => App\Controller\User::class], function(){
    Router::get('login', 'login');
    Router::get('register', 'register');
});

Router::get('home',[ App\Controller\Dashboard::class,  'MainDahboard'], 'auth');