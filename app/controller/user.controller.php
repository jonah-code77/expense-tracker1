<?php
namespace App\Controller;
use App\Model\Users;
use App\Core\Session;
use App\Core\View;

class User {

    private $user;


    public function __construct()
    {
        Session::start();
        $this->user = new users();
    }

    //view Register Form
    public function register(){
        View::views("auth/register");
    }



    //login users
    public function logIn(){
        View::views("auth/login");
    }
}