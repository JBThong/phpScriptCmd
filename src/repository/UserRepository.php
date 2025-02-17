<?php

namespace App\Repository;

use PDO;
use PDOException;
use App\DTO\UserDTO;

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
}
