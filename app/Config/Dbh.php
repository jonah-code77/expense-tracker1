<?php
namespace App\Config;


use Pdo;
use PDOException;

class Dbh{
    private static $instance = null;

    protected $conn;

    private function __construct(){
      $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pwd = $_ENV['DB_PASS'];

        try {
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $user, $pwd, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
           die("Connection failed: ". $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }

 }