<?php
namespace App\Http\Controller;
use App\Model\Users;
use App\Core\Session;
use App\Core\View;

class User {

    //view Register Form
    public function register(){
        View::views("auth/register");
    }



    //login users
    public function login(){
        View::views("auth/login");
    }
}