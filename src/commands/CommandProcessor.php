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
     * CommandProcessor constructor.
     *
     * @param DatabaseService $dbService The database service instance.
     */
    public function __construct(DatabaseService $dbService)
    {
        $this->dbService = $dbService;
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
}
