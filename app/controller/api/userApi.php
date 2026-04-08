<?php
namespace App\Controller\Api;
use App\Core\Session;
use App\Model\users;
use App\Core\View;

class UserApi{
    private $user;
    public function __construct(){
        $this->user = new Users();
    }
    public function register(){
        $errMsg = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if (isset($_POST['btn'])) {
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);

                if (!empty($name) && !empty($password) && !empty($email)) {
                    $checkEmailAvaliability = $this->user->user_exist($email);
                    if (!$checkEmailAvaliability) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $user = $this->user->reg_user($name,$email,$hashedPassword);
                        $newUser = $this->user->getUserById($user);

                        if($newUser){
                            Session::setSession('name',$newUser['name']);
                            Session::setSession('user_id',$newUser['id']);
                            header("location:". BASE_URL ."/home");
                            exit;
                        }  else{
                            die("not fetched");
                        }                      
                    }else{
                        $errMsg[] = "Email not avaliable";
                    }
                }else{
                    $errMsg[] = "Please fill all inputs";
                }
             }else{
                $errMsg[] = "Sign Up";
             }
        }else{
            jsonResApi::Response([
                'status' => 'error',
                'errMsg' => 'invalid method'
            ], 500);
        }
        View::views('auth/register',['errMsg'=>$errMsg]);
    }
}