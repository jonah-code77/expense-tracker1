<?php
namespace App\Model;

use App\Core\Model;

class transactions extends Model {

    private function baseQuery(){
        return "FROM transactions t 
                JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ?";
    }


    //TOTALS 
    public function getTotals($userId){
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(c.type)) = 'income'  THEN t.amount ELSE 0 END), 0) AS income,
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(c.type)) = 'expenses' THEN t.amount ELSE 0 END), 0) AS expense
                " . $this->baseQuery() . "
                  AND MONTH(t.date) = MONTH(CURRENT_DATE())
                  AND YEAR(t.date)  = YEAR(CURRENT_DATE())";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: ['income' => 0, 'expense' => 0];
    }

    public function getLastMonthTotals($userId){
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(c.type)) = 'income'  THEN t.amount ELSE 0 END), 0) AS income,
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(c.type)) = 'expenses' THEN t.amount ELSE 0 END), 0) AS expense
                " . $this->baseQuery() . "
                  AND MONTH(t.date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
                  AND YEAR(t.date)  = YEAR(CURRENT_DATE()  - INTERVAL 1 MONTH)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: ['income' => 0, 'expense' => 0];
    }

    //ACTIVITY FEED 

    public function getRecentActivity($userId, int $limit = 3){
        // Transactions this month
        $txSql = "SELECT 'transaction' AS activity_type, t.id, t.amount, t.description, t.date AS activity_date,
                    c.name AS category, c.type AS tx_type " . $this->baseQuery() . " ORDER BY t.date DESC LIMIT 50";

        // Recently created categories (last 30 days)
        $catSql = "SELECT 'category' AS activity_type, id, NULL AS amount, name AS description, created_at AS activity_date,
                    name AS category,type AS tx_type
                  FROM categories WHERE user_id = ? AND is_default = 0
                  AND created_at >= (CURRENT_DATE() - INTERVAL 30 DAY)
                  ORDER BY created_at DESC LIMIT 20";

        $activities = [];

        $stmt = $this->conn->prepare($txSql);
        $stmt->execute([$userId]);
        $activities = array_merge($activities, $stmt->fetchAll());

        // Only run category query if categories table has created_at
        try {
            $stmt = $this->conn->prepare($catSql);
            $stmt->execute([$userId]);
            $activities = array_merge($activities, $stmt->fetchAll());
        } catch (\Exception $e) {
            
        }

        // Sort combined results by date descending
        usort($activities, fn($a, $b) =>
            strtotime($b['activity_date']) <=> strtotime($a['activity_date'])
        );

        return array_slice($activities, 0, $limit);
    }

    public function getRecentTransaction($userId, $limit = 6){
        $sql = "SELECT t.*, c.name AS category, c.type
                " . $this->baseQuery() . "
                ORDER BY t.date DESC
                LIMIT $limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

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

    public function Search($userId, $search){
        $search = "%$search%";
        $sql = "SELECT t.*, c.name, c.type 
                FROM transactions t 
                JOIN categories c ON t.category_id = c.id 
                WHERE (c.name LIKE ? OR t.description LIKE ? OR c.type LIKE ? OR t.amount LIKE ?) 
                  AND t.user_id = ? 
                ORDER BY t.date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$search, $search, $search, $search, $userId]);
        return $stmt->fetchAll();
    }

    public function getFiltered($userId, $filter){
        $sql = "SELECT t.*, c.name, c.type 
                " . $this->baseQuery() . " 
                  AND MONTH(date) = MONTH(CURRENT_DATE()) 
                  AND YEAR(date) = YEAR(CURRENT_DATE())";
        $params = [$userId];

        if (!empty($filter['search'])) {
            $sql .= " AND (c.name LIKE ? OR t.description LIKE ? OR c.type LIKE ? OR t.amount LIKE ?)";
            $search = "%{$filter['search']}%";
            array_push($params, $search, $search, $search, $search);
        }
        if (!empty($filter['type'])) {
            $sql .= " AND c.type = ?";
            $params[] = $filter['type'];
        }
        if (!empty($filter['getDate'])) {
            $sql .= " AND DATE(t.date) = ?";
            $params[] = $filter['getDate'];
        }
        if (!empty($filter['end_date'])) {
            $sql .= " AND t.date <= ?";
            $params[] = $filter['end_date'];
        }

        $sql .= " ORDER BY t.date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTransactionsById($userId, $id){
        $sql = "SELECT t.*, c.name 
                FROM transactions t
                JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = ? AND t.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $id]);
        return $stmt->fetch();
    }

    public function create($userId, $categoryId, $amount, $description){
        $sql = "INSERT INTO transactions (user_id, category_id, amount, description, date) VALUES(?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $categoryId, $amount, $description]);
    }

    public function updateTransaction($userId, $id, $amount, $description){
        $sql = "UPDATE transactions SET amount = ?, description = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$amount, $description, $id, $userId]);
    }

    public function DeleteTransaction($id, $userId){
        $sql = "DELETE FROM transactions WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }

public function getCategoryBreakdown($userId): array
{
    $sql = "SELECT 
                c.id,
                c.name,
                c.type,
                COUNT(t.id)                AS transactions,
                COALESCE(SUM(t.amount), 0) AS total
            FROM categories c
            LEFT JOIN transactions t 
                ON  t.category_id = c.id
                AND t.user_id     = c.user_id
            WHERE c.user_id = ?
              AND LOWER(TRIM(c.type)) = 'expenses'
            GROUP BY c.id, c.name, c.type
            ORDER BY total DESC LIMIT 5";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

    public function getTransactionsByCategory($categoryId, $userId){
        $sql = "SELECT * FROM transactions WHERE category_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoryId, $userId]);
        return $stmt->fetchAll();
    }

    public function getTransactionReport($userId, $startDate, $endDate){
        $sql = "SELECT t.*, c.name, c.type 
                " . $this->baseQuery() . "
                AND DATE(date) >= ?";
        if ($endDate) {
            $sql .= " AND DATE(date) <= ?";
        }
        $sql .= " ORDER BY date ASC";
        $stmt = $this->conn->prepare($sql);
        $params = $endDate ? [$userId, $startDate, $endDate] : [$userId, $startDate];
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getMonthlyExpenses($userId, $month, $year){
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(c.type)) = 'income'  THEN t.amount ELSE 0 END), 0) AS income,
                    COALESCE(SUM(CASE WHEN LOWER(TRIM(c.type)) = 'expense' THEN t.amount ELSE 0 END), 0) AS expense
               " . $this->baseQuery() . "
                  AND MONTH(date) = ?
                  AND YEAR(date) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $month, $year]);
        return $stmt->fetch();
    }
}