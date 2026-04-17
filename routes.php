<?php
use App\Core\Router;
use App\Http\Controller\Dashboard;
use App\Http\Controller\User;

//Public Routes
Router::group('', ['controller' => User::class], function(){
    Router::get('login', 'login');
    Router::get('register', 'register');
});

//Home Routes
// Router::group('home', ['controller'=> Dashboard::class,
// 'middleware'=> 'auth'
// ], function(){
//     Router::get('', 'index');
// });

Router::get('home', [Dashboard::class, 'index'], 'auth');
