<?php

namespace App\Services\Database;

use PDO;
use PDOException;


/**
 * Class DatabaseService
 * 
 * Provides various database services, such as creating the users table, validating data, etc.
 */
class DatabaseService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Create the users table in the database.
     */
    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100),
            surname VARCHAR(100),
            email VARCHAR(100) UNIQUE
        );";

        try {
            $this->pdo->exec($sql);
            echo "Users table created or already exists.\n";
        } catch (PDOException $e) {
            echo "Error creating table: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
}
