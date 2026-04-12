<?php
namespace App\Controller\Api;

use App\Core\JsonResApi;
use App\Service\AuthService;

class AuthApi{
    private $auth;

    public function __construct(){
        $this->auth = new AuthService();
    }

    public function register(){
   
       $data = [
        'name' => $_POST["name"] ?? "",
        'email' => $_POST["email"] ??"",
        'password' => $_POST["password"] ??""
       ];

        $result = $this->auth->register($data);
        return JsonResApi::Response($result['data'], $result['status']);  
    }

    public function login(){
        $data = [
            'nameOrEmail' => ucfirst(trim($_POST['nameorEmail'])),
            'password' => trim($_POST['password'])
        ];

        $result = $this->auth->login($data);
        return JsonResApi::Response($result['data'], $result['status']);

    }
}