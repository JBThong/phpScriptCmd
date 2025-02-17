<?php

namespace App\Commands;

use App\Services\Database\DatabaseService;

/**
 * Class CommandProcessor
 * 
 * Handles the command line options and processes them.
 * It includes processing for table creation, file uploading, etc.
 */
class CommandProcessor
{
    /**
     * @var DatabaseService The service responsible for interacting with the database.
     */
    private $dbService;

    /**
     * @var CSVProcessingService The service responsible for processing CSV files.
     */
    private $csvProcessingService;

    /**
     * @var LogService The service responsible for logging.
     */
    private $logService;

    /**
     * @var UserService The service responsible for user-related operations.
     */
    private $userService;

    /**
     * CommandProcessor constructor.
     *
     * @param DatabaseService $dbService The database service instance.
     */
    public function __construct(DatabaseService $dbService)
    {
        $this->dbService = $dbService;
    }

    /**
     * Set the CSVProcessingService instance.
     *
     * @param CSVProcessingService $csvProcessingService
     */
    public function setCSVProcessingService($csvProcessingService)
    {
        $this->csvProcessingService = $csvProcessingService;
    }

    /** 
     * Set the LogService instance.
     * 
     */
    public function setLogService($logService)
    {
        $this->logService = $logService;
    }

    /**
     * Set the UserService instance.
     *
     * @param UserService $userService
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
    }

    /**
     * Process the "create_table" command to create the users table.
     *
     * @return void
     */
    public function processCreateTable()
    {
        $this->dbService->createTable();
    }

    public function processCSVFile($file)
    {
        $users = $this->csvProcessingService->processCsv($file);
        var_dump($users);

        foreach ($users as $user) {
            // create user.
            // $this->userService->($user);
        }
    }
}
