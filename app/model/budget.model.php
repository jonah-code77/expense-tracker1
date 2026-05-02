<?php
namespace App\Model;

use App\Core\Model;

class Budget extends Model{
 
    public function getCurrentMonthBudgets($userId){
        $sql = "SELECT b.id, b.category_id, c.name, b.amount AS `limit`,
                    COALESCE( SUM( CASE WHEN MONTH(t.date) = b.month 
                    AND YEAR(t.date) = b.year THEN t.amount ELSE 0 END ), 0 ) 
                    AS spent FROM budgets b
                JOIN categories c ON b.category_id = c.id LEFT JOIN transactions t 
                    ON  t.category_id = b.category_id AND t.user_id = b.user_id
                WHERE b.user_id = ?
                  AND b.month = MONTH(CURRENT_DATE())
                  AND b.year = YEAR(CURRENT_DATE())
                GROUP BY b.id, b.category_id, c.name, b.amount
                ORDER BY COALESCE(
                        SUM(
                            CASE 
                                WHEN MONTH(t.date) = b.month 
                                 AND YEAR(t.date)  = b.year
                                THEN t.amount 
                                ELSE 0 
                            END
                        ), 0
                    ) / b.amount DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll() ?: [];
    }

    public function hasEverHadBudget($userId){
        $sql  = "SELECT COUNT(*) FROM budgets WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getCategoriesWithoutBudget($userId){
        $sql = "SELECT c.id, c.name FROM categories c
                WHERE c.user_id = ?
                  AND LOWER(TRIM(c.type)) = 'expense'
                  AND c.id NOT IN (
                      SELECT category_id FROM budgets
                      WHERE user_id = ?
                        AND month   = MONTH(CURRENT_DATE())
                        AND year    = YEAR(CURRENT_DATE())
                  )
                ORDER BY c.name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll() ?: [];
    }

    public function getById($id, $userId){
        $sql  = "SELECT b.*, c.name FROM budgets b
                 JOIN categories c ON b.category_id = c.id
                 WHERE b.id = ? AND b.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id, $userId]);
        return $stmt->fetch() ?: false;
    }

    public function getAllBudgets($userId){
        $sql = "SELECT b.*, c.name
                FROM budgets b
                JOIN categories c ON b.category_id = c.id
                WHERE b.user_id = ?
                ORDER BY b.year DESC, b.month DESC, c.name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll() ?: [];
    }


    public function create($userId, $categoryId, $amount){
        $sql = "INSERT IGNORE INTO budgets 
                    (user_id, category_id, amount, month, year)
                VALUES 
                    (?, ?, ?, MONTH(CURRENT_DATE()), YEAR(CURRENT_DATE()))";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $categoryId, $amount]);
    }

    public function update($id, $userId, $amount){
        $sql  = "UPDATE budgets SET amount = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$amount, $id, $userId]);
    }

    public function delete($id, $userId){
        $sql  = "DELETE FROM budgets WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }
}