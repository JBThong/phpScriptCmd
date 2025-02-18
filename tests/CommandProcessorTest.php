<?php

use PHPUnit\Framework\TestCase;
use App\Commands\CommandProcessor;
use App\Services\Database\DatabaseService;
use App\Services\Log\LogService;
use App\Services\CSVProcessor\CSVProcessingService;
use App\Services\User\UserService;
use App\DTO\UserDTO;

class CommandProcessorTest extends TestCase
{
    private $dbServiceMock;
    private $csvProcessingServiceMock;
    private $logServiceMock;
    private $userServiceMock;
    private $commandProcessor;

    protected function setUp(): void
    {
        $this->dbServiceMock = $this->createMock(DatabaseService::class);
        $this->csvProcessingServiceMock = $this->createMock(CSVProcessingService::class);
        $this->logServiceMock = $this->createMock(LogService::class);
        $this->userServiceMock = $this->createMock(UserService::class);

        $this->commandProcessor = new CommandProcessor($this->dbServiceMock);

        $this->commandProcessor->setCSVProcessingService($this->csvProcessingServiceMock);
        $this->commandProcessor->setLogService($this->logServiceMock);
        $this->commandProcessor->setUserService($this->userServiceMock);
    }

    public function testProcessCreateTable()
    {
        $this->dbServiceMock->expects($this->once())
            ->method('createUsersTable');

        $this->commandProcessor->processCreateTable();
    }

    public function testProcessCSVFileDryRun()
    {
        $userDTO1 = new UserDTO("John", "Doe", "john.doe@example.com");
        $userDTO2 = new UserDTO("Jane", "Doe", "jane.doe@example.com");
        $users = [$userDTO1, $userDTO2];

        $this->csvProcessingServiceMock->expects($this->once())
            ->method('processCsv')
            ->willReturn($users);

        $this->logServiceMock->expects($this->once())
            ->method('logInfo')
            ->with($this->stringContains('The number of Users in the CSV file'));

        $this->expectOutputString(
            "Dry run - would insert: John Doe (john.doe@example.com)\n" .
            "Dry run - would insert: Jane Doe (jane.doe@example.com)\n"
        );

        $this->commandProcessor->processCSVFile('dummy.csv', true);
    }

    public function testProcessCSVFileWithoutDryRun()
    {
        $userDTO1 = new UserDTO("John", "Doe", "john.doe@example.com");
        $userDTO2 = new UserDTO("Jane", "Doe", "jane.doe@example.com");
        $users = [$userDTO1, $userDTO2];

        $this->csvProcessingServiceMock->expects($this->once())
            ->method('processCsv')
            ->willReturn($users);

        $this->logServiceMock->expects($this->once())
            ->method('logInfo')
            ->with($this->stringContains('The number of Users in the CSV file'));

        $this->userServiceMock->expects($this->exactly(2))
            ->method('createUser')
            ->with($this->isInstanceOf(UserDTO::class));

        $this->commandProcessor->processCSVFile('dummy.csv', false);
    }
}
