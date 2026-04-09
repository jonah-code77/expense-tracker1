<?php
namespace App\Core;

use App\Config\Dbh;

class Model {
    protected $conn;

    public function __construct() {
        $this->conn = Dbh::getInstance()->getConnection();
    }
}