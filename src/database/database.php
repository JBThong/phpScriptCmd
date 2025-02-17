<?php

class Database {
    private $pdo;
    
    public function __construct($host, $username, $password, $dbName) {
        $dsn = "pgsql:host=$host;port=5432;dbname=$dbName";
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
            exit(1);
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
