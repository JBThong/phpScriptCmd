<?php

use PHPUnit\Framework\TestCase;
use App\Services\User\UserService;
use App\Repository\UserRepository;
use App\DTO\UserDTO;
use App\Services\Log\LogService;

class UserServiceTest extends TestCase
{
    private $userRepositoryMock;
    private $logServiceMock;
    private $userService;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->logServiceMock = $this->createMock(LogService::class);
        $this->pdoMock = $this->createMock(PDO::class);

        $this->userService = new UserService($this->userRepositoryMock, $this->pdoMock);
        $this->userService->setLogService($this->logServiceMock);
    }


    public function testCreateUserValidEmail()
    {
        $userDTO = new UserDTO("John", "Doe", "john.doe@example.com");

        $this->userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->with($userDTO)
            ->willReturn(true);

        $result = $this->userService->createUser($userDTO);

        $this->assertTrue($result);
    }

    public function testCreateUserInvalidEmail()
    {
        $userDTO = new UserDTO("John", "Doe", "invalid-email");
        $result = $this->userService->createUser($userDTO);

        $this->assertFalse($result);
    }

    public function testCreateUserWithDatabaseError()
    {
        $userDTO = new UserDTO('John', 'Doe', 'john.doe@example.com');

        $this->userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->with($this->equalTo($userDTO))
            ->will($this->throwException(new PDOException('Database error')));

        $this->logServiceMock->expects($this->once())
            ->method('logError')
            ->with($this->stringContains('Database error'));

        $result = $this->userService->createUser($userDTO);

        $this->assertFalse($result);
    }
}
