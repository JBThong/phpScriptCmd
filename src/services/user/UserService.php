<?php

namespace App\Services\User;

use App\Repository\UserRepository;
use PDO;
use PDOException;
use App\DTO\UserDTO;
use App\Services\Log\LogService;

/**
 * Class UserService
 *
 * Contains business logic related to user operations, such as processing CSV files and handling user creation.
 */
class UserService {
    private $userRepository;
    private $pdo;
    private $logService;

    public function __construct(UserRepository $userRepository, PDO $pdo) {
        $this->userRepository = $userRepository;
        $this->pdo = $pdo;
    }

    /**
     * Set the LogService instance.
     *
     * @param LogService $logService
     */
    public function setLogService(LogService $logService) {
        $this->logService = $logService;
    }


    /**
     * Create user service.
     *
     * @param UserDTO $userDTO The UserDTO instance containing user data.
     *
     * @return bool True on successful insertion, false otherwise.
     */
    public function createUser(UserDTO $userDTO) {

        if (!$userDTO->isValidEmail()) {
            $this->logService->logError("Error inserting user: " . $userDTO->getName() . " - Invalid email address.");
            return false;
        }

        try {
            $result = $this->userRepository->createUser($userDTO);
            $this->logService->logInfo("User " . $userDTO->getName() . " added successfully.");
        } catch (PDOException $e) {
            $this->logService->logError("Error inserting user: " . $userDTO->getName() . " - " . $e->getMessage());
            return false;
        }

        return $result;
    }
}
