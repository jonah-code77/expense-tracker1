<?php
namespace App\Service;

use App\Core\Session;
use App\Model\Users;

class AuthService {
    private Users $user;

    public function __construct(){
        $this->user = new Users();
    }

    public function register(array $data) {
   
        if ($this->user->findByEmail($data['email'])) {
            return [
                'status' => 400,
                'data'   => [
                    'status' => 'error', 
                    'msg' => 'Email already exists'
                ]
            ];
        }

        $userId = $this->user->createUser([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        if (!$userId) {
            return [
                'status' => 500,
                'data'   => [
                    'status' => 'error', 
                    'msg' => 'Registration failed'
                ]
            ];
        }

        $newUser = $this->user->getUserById($userId);
        if (!$newUser) {
            return [
                'status' => 500,
                'data'   => ['status' => 'error', 
                'msg' => 'Failed to fetch new user'
                ]
            ];
        }

        Session::setSession('name',    $newUser['name']);
        Session::setSession('user_id', $newUser['id']);
        Session::setSession('email',   $newUser['email']);

        return [
            'status' => 200,
            'data'   => ['status' => 'success', 
            'redirect' => BASE_URL . '/home'
            ]
        ];
    }

    public function login(array $data) {
        $user = $this->user->findByEmailOrName($data['nameOrEmail']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return [
                'status' => 401,
                'data'   => ['status' => 'error', 
                'msg' => 'Invalid credentials'
                ]
            ];
        }

        Session::setSession('name',    $user['name']);
        Session::setSession('user_id', $user['id']);
        Session::setSession('email',   $user['email']);

        return [
            'status' => 200,
            'data'   => ['status' => 'success', 
            'redirect' => BASE_URL . '/home'
            ]
        ];
    }
}