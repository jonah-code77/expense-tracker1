<?php
use App\Core\Router;
use App\Controller\Dashboard;
use App\Controller\User;
//Public Routes
Router::group('', ['controller' => User::class], function(){
    Router::get('login', 'login');
    Router::get('register', 'register');
});

//Home Routes
Router::group('home', ['controller'=> Dashboard::class,
'middleware'=> 'auth'
], function(){
    Router::get('', 'MainDahboard');
    Router::get('', 'dashboard');
});
