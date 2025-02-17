<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use App\Commands\CommandProcessor;
use App\Database\Database;
use App\Services\Database\DatabaseService;

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

// Create a new Database object and establish a connection
$db = new Database($dbHost, $dbUsername, $dbPassword, $dbName);
$pdo = $db->getConnection();
if ($pdo) {
    echo "Connected to the PostgreSQL database successfully.\n";
} else {
    echo "Failed to connect to the PostgreSQL database.\n";
    exit(1);
}

// Create the DatabaseService and CommandProcessor objects
$dbService = new DatabaseService($pdo);
$commandProcessor = new CommandProcessor($dbService);

// Handle command options
if (isset($options['create_table'])) {
    // If 'create_table' is specified, create the users table
    $commandProcessor->processCreateTable();
}