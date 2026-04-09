<?php
namespace App\Controller\Api;
use App\Core\Session;
use App\Model\users;
use App\Core\View;
use App\Core\Validator;

class UserApi{
    private $user;

    public function __construct(){
        $this->user = new Users();
    }

    public function registerr(){
       $errMsg = [];
       $name = $_POST["name"];
       $email = $_POST["email"];
       $password = $_POST["password"];

       //validation
       $err = Validator::required($name, 'Name is required');
       if($err) $errMsg['name'] = $err;

       $email = Validator::required($email, 'email is required');
       if($err) $errMsg['email'] = $err;

       $err = Validator::email($email);
       if($err) $errMsg['email'] = $err;

       $err = Validator::required($password, 'password is required');
       if($err) $errMsg['password'] = $err;
       
        if (!empty($errMsg)) {
            jsonResApi::Response([
                "status" => "error",
                "errors" => $errMsg
            ],405);
                
        }

       $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
       $user = $this->user->reg_user($name,$email,$hashedPassword);
       $newUser = $this->user->getUserById($user);

       if($newUser){
            Session::setSession('name',$newUser['name']);
            Session::setSession('user_id',$newUser['id']);

            jsonResApi::Response([
                'status'=> 'success',
                'redirect' => BASE_URL .'/home'
            ],200);

       }else{
        jsonResApi::Response([
            'status'=> 'error',
            'errors'=> 'failed to fetch new user'
        ],400);
       }
    }

    public function logInn(){
        $errMsg = [];
        $nameOrEmail = ucfirst(trim($_POST['nameorEmail']));
        $password = trim($_POST['password']);

       //validation
       $err = Validator::required($nameOrEmail, 'Name or Email is required');
       if($err) $errMsg['name'] = $err;
       
       $err = Validator::required($password, 'password is required');
       if($err) $errMsg['password'] = $err;
            
        if (!empty($errMsg)) {
            jsonResApi::Response([
                "status" => "error",
                "msg" => $errMsg
            ],400);         
        }
        $user = $this->user->logIn($nameOrEmail);
        if($user && password_verify($password,$user['password'])){
            Session::setSession("name",$user["name"]);
            Session::setSession("user_id",$user["id"]);

            $redirect = BASE_URL . '\home'; 
            jsonResApi::Response([
                'status' => 'success',
                'redirect' => $redirect   
            ]);

        }else{
            jsonResApi::Response([
                'status'=> 'error',
                'msg'=> ['login' =>'invalid details']
            ],401);
        }
    }
}