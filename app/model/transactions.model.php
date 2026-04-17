<?php
namespace App\Model;

use App\Core\Model;

class transactions extends Model {

    private function baseQuery(){
        return "FROM transactions t 
                JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ?";
    }

    //get all transactions
    public function getMonthly($userId){
        $sql = "SELECT t.*, c.name, c.type 
                " . $this->baseQuery() . " 
                AND MONTH(t.date) = MONTH(CURRENT_DATE()) 
                AND YEAR(t.date) = YEAR(CURRENT_DATE())
                ORDER BY t.date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();       
    }

    

    //search
    public function Search($userId,$search){
        $search = "%$search%";
        $sql = "SELECT t.*, c.name, c.type FROM transactions t JOIN categories c ON t.category_id = c.id WHERE (c.name LIKE ? OR t.description LIKE ? OR c.type LIKE ? OR t.amount LIKE ?) AND t.user_id = ? ORDER BY t.date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$search, $search, $search, $search, $userId]);
        return $stmt->fetchAll();
    }

    //search and filter
    public function getFiltered($userId, $filter){
        $sql = "SELECT t.*, c.name, c.type FROM transactions t JOIN categories c ON t.category_id = c.id WHERE t.user_id = ? AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
        $params = [$userId];

        //search
        if(!empty($filter['search'])){
            $sql .= "AND (c.name LIKE ? OR t.description LIKE ? OR c.type LIKE ? OR t.amount LIKE ?)";
            $search = "%{$filter['search']}%";
            array_push($params,$search,$search,$search,$search);
        }

        //filter type
        if(!empty($filter['type'])){
            $sql .= "AND c.type = ?";
            $params[] = $filter['type'];
        }

        //filter date range
        if(!empty($filter['getDate'])){
            $sql .= "AND DATE(t.date) = ?";
            $params[] = $filter['getDate'];
        }

        if(!empty($filter['end_date'])){
            $sql .= "AND t.date <= ?";
            $params[] = $filter['end_date'];
        }

        $sql .= "ORDER BY t.date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(); 
    }

    //get recent transaction
   public function getRecentTransaction($userId, $limit = 3){
        $sql = "SELECT transactions .*, categories.name, categories.type FROM transactions JOIN categories 
        ON transactions.category_id = categories.id WHERE transactions.user_id = ? 
        AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) 
        ORDER BY transactions.date DESC LIMIT $limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(); 
    }

    public function getTransactionsById($userId,$id){
        $sql = "SELECT transactions .*, categories.name FROM transactions JOIN categories 
        ON transactions.category_id = categories.id WHERE transactions.user_id = ? AND transactions.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId,$id]);
        return $stmt->fetch();
    }


    //add to transaction
    public function create($userId,$categoryId,$amount, $description){
        $sql = "INSERT INTO transactions (user_id, category_id, amount, description, date) VALUES(?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $categoryId, $amount, $description]);
    }

    public function updateTransaction($userId,$id,$amount, $description){
            $sql = "UPDATE transactions SET amount = ?, description = ? WHERE id = ? AND user_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$amount, $description, $id, $userId]);
    }

    //Delete Transaction
    private function getTransactionCategoryId($id){
        $sql = "SELECT category_id FROM transactions WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
    
    public function DeleteTransaction($id, $userId){
        $sql = "DELETE FROM transactions WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id,$userId]);

    }

    //summary of amount spent in total
    public function getTotals($userId){
        $sql = "SELECT SUM(CASE WHEN trim(LOWER(c.type)) = 'income' THEN t.amount ELSE 0 END) AS total_income, 
        SUM(CASE WHEN trim(LOWER(c.type)) = 'expenses' THEN t.amount ELSE 0 END) AS total_expenses 
        FROM transactions t 
                JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ?
        AND MONTH(t.date) = MONTH(CURRENT_DATE()) 
        AND YEAR(t.date) = YEAR(CURRENT_DATE())";
        

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
        
    }

    public function getLastMonthTotals($userId){
        $sql = "SELECT 
                SUM(CASE WHEN LOWER(TRIM(c.type)) = 'income' THEN t.amount ELSE 0 END) as income,
                SUM(CASE WHEN LOWER(TRIM(c.type)) = 'expense' THEN t.amount ELSE 0 END) as expense
                FROM transactions t 
                JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ?
                AND MONTH(t.date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
                AND YEAR(t.date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetch();
    }

    public function getCategoryBreakdown($userId){
        $sql = "SELECT c.name, SUM(t.amount) as total 
                " . $this->baseQuery() . "
                AND LOWER(TRIM(c.type)) = 'expense'
                GROUP BY c.name";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function getTransactionsByCategory($categoryId,$userId){
        $sql = "SELECT * FROM transactions WHERE category_id = ? AND user_id = ?";
        $stmt =$this->conn->prepare($sql);
        $stmt->execute([$categoryId,$userId]);
        return $stmt->fetchAll();
    }

    //Transaction Model
    public function getTransactionReport($userId, $startDate, $endDate){
        $sql = "SELECT t.*, c.name, c.type FROM transactions t JOIN categories c ON t.category_id = c.id WHERE t.user_id = ?
                AND DATE(date) >= ?";

        if ($endDate) {
            $sql .= "AND DATE(date) <= ?"; 
        }

        $sql .= " ORDER BY date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getMonthlyExpenses($userId,$month,$year){
        $sql = "SELECT SUM(CASE WHEN trim(LOWER(c.type)) = 'income' THEN t.amount ELSE 0 END) AS total_income, 
        SUM(CASE WHEN trim(LOWER(c.type)) = 'expenses' THEN t.amount ELSE 0 END) AS total_expenses 
        FROM transactions t 
        JOIN categories c ON t.category_id = c.id WHERE t.user_id = ?
        AND MONTH(date) = ?
        AND Year(date) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId,$month,$year]);
        return $stmt->fetch();
    }

 


}