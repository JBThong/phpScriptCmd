<?php

namespace App\Services\CSVProcessor;

use App\DTO\UserDTO;
use App\Services\Log\LogService;

/**
 * Class CSVProcessingService
 * 
 * Handles reading and processing CSV files.
 */
class CSVProcessingService {
    private $logService;

    public function __construct(LogService $logService) {
        $this->logService = $logService;
    }

    /**
     * Process a CSV file and return an array of UserDTO objects.
     *
     * @param string $csvFile The path to the CSV file.
     * @return UserDTO[] An array of UserDTO objects.
     */
    public function processCsv($csvFile): array {
        if (!file_exists($csvFile)) {
            $this->logService->logError("Error: File does not exist: $csvFile");
            return [];
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file);

        $users = [];
        while ($row = fgetcsv($file)) {
            $userDTO = new UserDTO($row[0], $row[1], $row[2]);
            $users[] = $userDTO;
        }
        fclose($file);

        return $users;
    }
}
