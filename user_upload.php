<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use App\Commands\CommandProcessor;
use App\Database\DatabaseConnection;
use App\Services\Database\DatabaseService;

use App\Services\CSVProcessor\CSVProcessingService;
use App\Services\Log\LogService;
use App\Services\User\UserService;
use App\Repository\UserRepository;

$options = getopt("", ["file:", "create_table", "dry_run", "help", "u:", "p:", "h:"]);

if (isset($options['help'])) {
    echo "Usage: php user_upload.php [options]\n";
    echo "--file [csv file name]    : Specify the CSV file with user data\n";
    echo "--create_table            : Create the PostgreSQL 'users' table\n";
    echo "--dry_run                 : Perform a dry run without modifying the database\n";
    echo "-u                        : PostgreSQL username\n";
    echo "-p                        : PostgreSQL password\n";
    echo "-h                        : PostgreSQL host (default: localhost)\n";
    exit(0);
}

if (empty($options['file']) && !isset($options['create_table'])) {
    echo "Error: You must specify a CSV file with the --file option or use --create_table to create the table.\n";
    exit(1);
}

// Get database connection details
$dbHost = $options['h'] ?? 'localhost';
$dbUsername = $options['u'] ?? 'postgres';
$dbPassword = $options['p'] ?? '';
$dbName = DB_NAME;

$db = new DatabaseConnection($dbHost, $dbUsername, $dbPassword, $dbName);
$pdo = $db->getConnection();
if ($pdo) {
    echo "Connected to the PostgreSQL database successfully.\n";
} else {
    echo "Failed to connect to the PostgreSQL database.\n";
    exit(1);
}


// Initialize services
$logService = new LogService("log.txt");
$dbService = new DatabaseService($logService, $pdo);

$commandProcessor = new CommandProcessor($dbService);
$csvProcessingService = new CSVProcessingService($logService);
$commandProcessor->setCSVProcessingService($csvProcessingService);
$commandProcessor->setLogService($logService);

$userRepository = new UserRepository($pdo);
$userService = new UserService($userRepository, $pdo);
$userService->setLogService($logService);
$commandProcessor->setUserService($userService);

// Handle command options
if (isset($options['create_table'])) {
    $commandProcessor->processCreateTable();
}

if(isset($options['file'])) {
    $csvFile = $options['file'];
    $dryRun = isset($options['dry_run']);
    $commandProcessor->processCSVFile($csvFile, $dryRun);
}