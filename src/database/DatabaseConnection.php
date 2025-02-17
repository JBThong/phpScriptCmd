<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Services\Log\LogService;

/**
 * Class Database
 * 
 * This class is responsible for creating and managing a connection to a PostgreSQL database.
 * It uses PDO to establish a connection and provides access to the connection object.
 * 
 * @package App\Database
 */
class DatabaseConnection {
    /**
     * @var PDO $pdo The PDO instance used to interact with the database.
     */
    private $pdo;

    /**
     * @var LogService $logService The LogService instance for logging messages.
     */
    private $logService;


    /**
     * Database constructor.
     * 
     * This constructor establishes a connection to the PostgreSQL database using the provided
     * connection details (host, username, password, and database name).
     * It sets the PDO error mode to exception to throw errors on connection failure.
     * 
     * @param string $host The database host (e.g., 'localhost').
     * @param string $username The username for the database connection.
     * @param string $password The password for the database user.
     * @param string $dbname The name of the database to connect to.
     * 
     * @throws PDOException If the connection fails, a PDOException will be thrown.
     */
    public function __construct($host, $username, $password, $dbname) {
        $dsn = "pgsql:host=$host;port=5432;dbname=$dbname";
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $this->logService->logError("Database connection error: " . $e->getMessage());
            exit(1);
        }
    }

    /**
     * Get the PDO database connection instance.
     * 
     * This method returns the PDO connection object, which can be used to interact with the database.
     * 
     * @return PDO The PDO instance representing the database connection.
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * Set the LogService instance.
     *
     * @param LogService $logService
     */
    public function setLogService(LogService $logService) {
        $this->logService = $logService;
    }
}
