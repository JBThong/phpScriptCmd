<?php

namespace App\Commands;

use App\Services\Database\DatabaseService;
use App\Services\Log\LogService;

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
        $this->dbService->createUsersTable();
    }

    /**
     * Process the CSV file in batches to handle large files more efficiently.
     *
     * @param string $file The path to the CSV file.
     * @param bool $dryRun If true, just simulate the process without inserting users.
     * @param int $batchSize The number of users to process per batch.
     */
    public function processCSVFile($file, $dryRun = false, $batchSize = 1000)
    {
        if (!file_exists($file)) {
            $this->logService->logError("Error: File does not exist: $file");
            return;
        }

        $usersProcessed = 0;

        $batches = $this->csvProcessingService->processCsvInBatches($file, $batchSize);

        foreach ($batches as $batch) {
            if ($dryRun) {
                foreach ($batch as $user) {
                    echo "Dry run - would insert: " . $user->getName() . " " . $user->getSurname() . " (" . $user->getEmail() . ")\n";
                }
            } else {
                try {
                    $result = $this->userService->createUsers($batch);
                    if ($result) {
                        $this->logService->logInfo("Successfully created batch of " . count($batch) . " users.");
                    }
                } catch (\Exception $e) {
                    $this->logService->logError("Error creating batch of users. Error: " . $e->getMessage());
                }
            }

            $usersProcessed += count($batch);
        }

        $this->logService->logInfo("Total users processed: $usersProcessed");
    }
}
