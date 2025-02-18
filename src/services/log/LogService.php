<?php

namespace App\Services\Log;

/**
 * Class LogService
 *
 * Handles the logging functionality by writing messages to a log file with different levels (info, debug, warning, error, fatal).
 */
class LogService
{
    const LEVEL_INFO = 'info';
    const LEVEL_DEBUG = 'debug';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    const LEVEL_FATAL = 'fatal';

    /**
     * @var string $logFile Path to the log file.
     */
    private $logFile;

    /**
     * LogService constructor.
     *
     * @param string $logFile Path to the log file where messages will be written.
     */
    public function __construct($logFile = 'app_log.txt')
    {
        $logDir = __DIR__ . '/../../logs/';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $logFile = $logDir . $logFile;

        $this->logFile = $logFile;
    }

    /**
     * Write a log message to the log file with a specific level.
     *
     * @param string $message The log message to be written.
     * @param string $level   The log level (info, debug, warning, error, fatal).
     */
    public function writeLog($message, $level = self::LEVEL_INFO)
    {
        $validLevels = [self::LEVEL_INFO, self::LEVEL_DEBUG, self::LEVEL_WARNING, self::LEVEL_ERROR, self::LEVEL_FATAL];
        if (!in_array($level, $validLevels)) {
            $level = self::LEVEL_INFO;
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message\n";

        if (!file_exists($this->logFile)) {
            touch($this->logFile);
        }
        
        // Append the log message to the log file
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Log an info message.
     *
     * @param string $message The info message to be logged.
     */
    public function logInfo($message)
    {
        $this->writeLog($message, self::LEVEL_INFO);
    }

    /**
     * Log a debug message.
     *
     * @param string $message The debug message to be logged.
     */
    public function logDebug($message)
    {
        $this->writeLog($message, self::LEVEL_DEBUG);
    }

    /**
     * Log a warning message.
     *
     * @param string $message The warning message to be logged.
     */
    public function logWarning($message)
    {
        $this->writeLog($message, self::LEVEL_WARNING);
    }

    /**
     * Log an error message.
     *
     * @param string $message The error message to be logged.
     */
    public function logError($message)
    {
        $this->writeLog($message, self::LEVEL_ERROR);
    }

    /**
     * Log a fatal message.
     *
     * @param string $message The fatal message to be logged.
     */
    public function logFatal($message)
    {
        $this->writeLog($message, self::LEVEL_FATAL);
    }
}
