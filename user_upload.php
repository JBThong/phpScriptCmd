<?php

require_once __DIR__ . '/../src/Database.php';

$options = getopt("", ["file:", "create_table", "dry_run", "help", "u:", "p:", "h:"]);

if (isset($options['help'])) {
    echo "Usage: php user_upload.php [options]\n";
    exit(0);
}

if (empty($options['file'])) {
    echo "Error: You must specify a CSV file with the --file option.\n";
    exit(1);
}

$dbHost = $options['h'] ?? 'localhost';
$dbUsername = $options['u'] ?? 'postgres';
$dbPassword = $options['p'] ?? '';
$dbName = 'your_database_name';

// Create a new Database object and establish a connection
$db = new Database($dbHost, $dbUsername, $dbPassword, $dbName);
$pdo = $db->getConnection();
