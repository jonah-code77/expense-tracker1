<?php
namespace App\Model;

use App\Core\Model;

class Category extends Model {
    //add to category
    public function create($userId,$name,$type){
        $sql = "INSERT IGNORE INTO categories(user_id, name, type, created_at) VALUES(?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId,$name,$type]);
        return $this->conn->lastInsertId();
    }

    // in Category model, add a new method
    public function createDefault($userId, $name, $type){
        $sql  = "INSERT IGNORE INTO categories(user_id, name, type, is_default, created_at) 
                VALUES(?, ?, ?, 1, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $name, $type]);
    }

    //edit category
    public function updateCategory($name,$type,$id,$userId, $hasTransaction = false){
        if ($hasTransaction) {
            $sql = "UPDATE categories SET name = ? WHERE id = ? AND user_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name, $id,$userId]);
        }else{
            $sql = "UPDATE categories SET name = ?, type = ? WHERE id = ? AND user_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name, $type, $id,$userId]);
        }
    }

    //delete category
    public function deleteCategory($categoryId,$userId){

        //delete transactions tied to this category
        $sql = "DELETE FROM transactions WHERE category_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoryId,$userId]);

        //check for existing category
        $sql = "DELETE FROM categories WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoryId,$userId]);
        return true;
    }

    //get categories
    public function getCategories($userId){
        $sql = "SELECT categories.*,users.name AS user_name FROM categories JOIN users ON categories.user_id = users.id 
        WHERE categories.user_id = ? 
        AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())
        ORDER BY categories.created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getCategoryById($userId,$id){
        $sql = "SELECT categories.*,users.name AS user_name FROM categories JOIN users ON categories.user_id = users.id WHERE categories.user_id = ? AND categories.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId,$id]);
        return $stmt->fetch();
    }

    public function hasTransaction($categoryId,$userId){
        $sql = "SELECT COUNT(*) FROM transactions WHERE category_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoryId, $userId]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}