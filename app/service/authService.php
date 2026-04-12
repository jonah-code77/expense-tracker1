<?php
namespace App\Service;

use App\Core\Session;
use App\Core\Validator;
use App\Model\Users;

class AuthService{
    private $user;

    public function __construct(){
        $this->user = new Users();
    }

    public function register($data){
       $errMsg = [];

       //validation
       $err = Validator::required($data['name'], 'Name is required');
       if($err) $errMsg['name'] = $err;
       
       $err = Validator::required($data['email'], 'email is required');
       if($err) $errMsg['email'] = $err;
       
       $err = Validator::email($data['email']);
       if($err) $errMsg['email'] = $err;
       
       $err = Validator::required($data['password'], 'password is required');
       if($err) $errMsg['password'] = $err;

            
        if (!empty($errMsg)) {
            return [
                "status" => 400,
                'data' => ['status' => 'error', 'msg' => $errMsg]
            ];       
        }

        // Check if email exists
        if ($this->user->findByEmail($data['email'])) {
            return [
                'status' => 400,
                'data' => [
                    'status' => 'error', 
                    'msg' => 'Email already exists'
                    ]
            ];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT); 

        $data = [
            'name' => $data['name'],
            'email'=> $data['email'],
            'password'=> $hashedPassword
        ];
        $user = $this->user->createUser($data); 

        if (!$user) {
            return [
                'status'=> 400,
                'data'=> ['status'=> 'error','msg'=> 'Registration failed']
            ];
        } 

        $newUser = $this->user->getUserById($user);
        if (!$newUser) {
            return [
                'status'=> 400,
                'data'=> ['status'=> 'error','msg'=> 'Failed to fetch new user']
            ];
        }

        Session::setSession('name',$newUser['name']);
        Session::setSession('user_id',$newUser['id']);
        Session::setSession('email',$newUser['email']);

        return [
            'status' => 200,
            'data' => ['status' => 'success', 'redirect' => BASE_URL .'/home']
        ];

    }

    public function login($data){
        $errMsg = [];

        //validation
        $err = Validator::required($data['nameOrEmail'], 'Name or Email is required');
        if($err) $errMsg['name'] = $err;
        
        $err = Validator::required($data['password'], 'email is required');
        if($err) $errMsg['email'] = $err;
                    
        if (!empty($errMsg)) {
            return [
                "status" => 400,
                'data' => ['status' => 'error', 'msg' => $errMsg]
            ];       
        }
        $user = $this->user->findByEmailOrName($data['nameOrEmail']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return [
                'status' => 401,
                'data' => ['status' => 'error', 'msg' => 'Invalid credentials']
            ];
        }

        //set session
        Session::setSession('name',$user['name']);
        Session::setSession('user_id',$user['id']);
        Session::setSession('email',$user['email']);

        return [
            'status' => 200,
            'data' => ['status' => 'success', 'redirect' => '/home']
        ];
    }
}