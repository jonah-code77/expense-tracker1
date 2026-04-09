<?php
namespace App\Config;


use Pdo;
use PDOException;

class Dbh{
    private $host;
    private $dbname ;
    private $user;
    private $pwd;
    protected $conn;

    public function __construct(){
        $this->host = $_ENV['DB_HOST'];
        $this->dbname = $_ENV['DB_NAME'];
        $this->user = $_ENV['DB_USER'];
        $this->pwd = $_ENV['DB_PASS'];

        try {
            $dsn = "mysql:host=" . $this->host . "; dbname=" . $this->dbname;
            $this->conn = new PDO($dsn,$this->user,$this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        } catch (PDOException $e) {
           die("Connection failed: ". $e->getMessage());
        }
    }

 }