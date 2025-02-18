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

    /**
     * Process a CSV file and return an array of UserDTO objects.
     *
     * @param string $csvFile The path to the CSV file.
     * @param int $batchSize The number of users to process per batch.
     * @return array An array of UserDTO objects.
     */
    public function processCsvInBatches($csvFile, $batchSize = 1000) {
        if (!file_exists($csvFile)) {
            $this->logService->logError("Error: File does not exist: $csvFile");
            return [];
        }
    
        $file = fopen($csvFile, 'r');
        if (!$file) {
            $this->logService->logError("Error: Unable to open the file: $csvFile");
            return [];
        }
    
        $header = fgetcsv($file);
    
        if (!$header) {
            $this->logService->logError("Error: Unable to read header row.");
            fclose($file);
            return [];
        }
    
        $batch = [];
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) >= 3) {
                $userDTO = new UserDTO($row[0], $row[1], $row[2]);
                $batch[] = $userDTO;
            } else {
                $this->logService->logWarning("Skipping malformed row: " . implode(',', $row));
            }
    
            if (count($batch) >= $batchSize) {
                yield $batch;
                $batch = [];
            }
        }
    
        if (count($batch) > 0) {
            yield $batch;
        }
    
        fclose($file);
    }
}
