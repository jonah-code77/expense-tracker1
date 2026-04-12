<?php
namespace App\Model;

use App\Core\Model;

class Users extends Model {

    public function getUserById($id){
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    //Reg user
    public function createUser($data){
        $sql = "INSERT INTO users(name,email,password) VALUES(?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['name'],$data['email'],$data['password']]);
        return $this->conn->lastInsertId();
    }

    //user exist
    public function findByEmail($email){
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    //login 
    public function findByEmailOrName($value){
        $sql = "SELECT * FROM users WHERE name = ? OR email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$value,$value]);
        return $stmt->fetch();    
    }
    
}