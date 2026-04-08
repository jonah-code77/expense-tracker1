<?php
namespace App\Model;
use App\Config\Dbh\Dbh;

class users extends Dbh {

    public function getUserById($id){
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    //Reg user
    public function reg_user($name,$email,$password){
        $sql = "INSERT INTO users(name,email,password) VALUES(?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name,$email,$password]);
        return $this->conn->lastInsertId();
    }

    //user exist
    public function user_exist($email){
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    //login 
    public function logIn($nameorEmail,$password){
        $sql = "SELECT * FROM users WHERE name = ? OR email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nameorEmail,$nameorEmail]);
        $user = $stmt->fetch();
        if ($user && password_verify($password,$user['password'])) {
            return $user;
        }
        return false;    
    }
    
}