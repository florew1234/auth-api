<?php

namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database {
    private $conn;

    public function connect() {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];


        try {
            $this->conn = new PDO(
                "mysql:host={$host};dbname={$dbname}",
                $username,
                $password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    
}