<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\CreateUser;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use App\Domain\IRepository\IUserRepository;
use App\Domain\UseCase\User\CreateUser\CreateUser;
use App\Domain\UseCase\User\CreateUser\CreateUserInputData;

class CreateUserTest extends TestCase
{
    public function test_execute_return_user(): void
    {
        $userIterfaceRepositoryMock = $this->createMock(IUserRepository::class);

        $userIterfaceRepositoryMock->expects($this->once())
            ->method('createUser')
            ->willReturn(new User([
                1,
                'John Doe',
                'john@example.com',
                'password123',
                '2024-04-28 12:00:00',
                '2024-04-28 12:00:00',
                '2024-04-28 12:00:00'
            ]));

        $createUser = new CreateUser( $userIterfaceRepositoryMock);
        $inputData = new CreateUserInputData('John Doe', 'john@example.com',  'password123');
        $result = $createUser->execute($inputData);

        $this->assertInstanceOf(User::class, $result);
    }

    public function test_execute_return_user_null(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);

        $userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->willReturn(new User());

        $createUser = new CreateUser($userRepositoryMock);

        $inputData = new CreateUserInputData('John Doe', 'john@example.com',  'password123');
        
        $createUser->execute($inputData);
    }

}