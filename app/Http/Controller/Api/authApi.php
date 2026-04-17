<?php
namespace App\Http\Controller\Api;

use App\Core\JsonResApi;
use App\Http\Request\Auth\LoginRequest;
use App\Http\Request\Auth\RegisterRequest;
use App\Service\AuthService;

class AuthApi {
    private AuthService $auth;

    public function __construct(){
        $this->auth = new AuthService();
    }

    public function register(){
        $request = new RegisterRequest($_POST);

        if (!$request->validate()) {
            JsonResApi::Response([
                'status' => 'error',
                'msg' => $request->errors()
            ], 400);
        }

        $result = $this->auth->register($request->data());
        JsonResApi::Response($result['data'], $result['status']);
    }

    public function login(){
        
        $request = new LoginRequest($_POST);

        if (!$request->validate()) {
            JsonResApi::Response([
                'status' => 'error',
                'msg' => $request->errors()
            ], 400);
        }
        

        $result = $this->auth->login($request->data());
        JsonResApi::Response($result['data'], $result['status']);
    }
}