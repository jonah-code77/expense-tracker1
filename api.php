<?php
use App\Core\Router;

Router::group('', ['controller' => App\Controller\Api\UserApi::class], function () {
    Router::post('registerr', 'registerr');
    Router::post('logInn', 'logInn');
});