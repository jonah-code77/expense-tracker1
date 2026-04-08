<?php
namespace App\Controller;
use App\Model\users;
use App\Core\Session;
use App\Core\View;

class user {

    private $user;


    public function __construct()
    {
        Session::start();
        $this->user = new users();
    }

    //view Register Form
    public function register(){}
    //registration controller



    //login users
    public function logIn(){
        $errMsg = [];
        if (isset($_POST['btn'])) {
            $nameorEmail = ucfirst(trim($_POST['nameorEmail']));
            $password = trim($_POST['password']); 

            if (!empty($nameorEmail && $password)) {
                $user = $this->user->logIn($nameorEmail,$password);
                if($user){
                    Session::setSession('name',$user['name']);
                    Session::setSession('user_id',$user['id']);
                    header("location:". BASE_URL ."/home");
                    
                }else{
                    $errMsg[] =  "invalid details";
                }

                
            }else{
                $errMsg[] = "please fill in details";
            }

        }
        View::views('auth/login',['errMsg'=>$errMsg]);
    }
}