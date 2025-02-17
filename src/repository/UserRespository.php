<?php

/**
 * Class UserRepository
 *
 * Handles database operations related to users, such as inserting users into the database.
 */
class UserRepository {
    /**
     * @var PDO $pdo The PDO connection instance.
     */
    private $pdo;

    /**
     * UserRepository constructor.
     *
     * @param PDO $pdo The PDO connection instance.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create the 'users' table in the database if it does not exist.
     */
    public function createTable() {
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
