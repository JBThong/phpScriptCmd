<?php

namespace App\Repository;

use PDO;
use PDOException;
use App\DTO\UserDTO;
use App\Services\Log\LogService;

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
     * @var LogService $logService The LogService instance.
     */
    private $logService;

    /**
     * UserRepository constructor.
     *
     * @param PDO $pdo The PDO connection instance.
     */
    public function __construct(PDO $pdo, LogService $logService) {
        $this->pdo = $pdo;
        $this->logService = $logService;
    }

    /**
     * Insert a new user into the database.
     *
     * @param UserDTO $userDTO The UserDTO instance containing user data.
     *
     * @return bool True on successful insertion, false otherwise.
     */
    public function createUser(UserDTO $userDTO) {
        $sql = "INSERT INTO users (name, surname, email) 
                VALUES (:name, :surname, :email)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $userDTO->getName());
        $stmt->bindValue(':surname', $userDTO->getSurname());
        $stmt->bindValue(':email', $userDTO->getEmail());

        return $stmt->execute();
    }

    /**
     * Insert multiple users into the database in one batch.
     *
     * @param array $users An array of UserDTO instances.
     *
     * @return bool True on successful insertion, false otherwise.
     */
    public function createUsers(array $users) {
        if (empty($users)) {
            return false;
        }

        $sql = "INSERT INTO users (name, surname, email) VALUES ";

        $placeholders = [];
        $params = [];

        foreach ($users as $user) {
            $placeholders[] = "(?, ?, ?)";
            $params[] = $user->getName();
            $params[] = $user->getSurname();
            $params[] = $user->getEmail();
        }

        $sql .= implode(", ", $placeholders);
        $sql .= " ON CONFLICT(email) DO NOTHING";

        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                $this->logService->logError("Error inserting users: " . implode(", ", $errorInfo));
            }
            return $result;
        } catch (\PDOException $e) {
            $this->logService->logError("Database error: " . $e->getMessage());
            return false;
        }
    }
}
