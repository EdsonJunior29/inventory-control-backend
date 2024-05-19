<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\GetUserByEmail;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use App\Domain\IRepository\IUserRepository;
use App\Domain\UseCase\User\GetUserByEmail\GetUserByEmail;
use App\Domain\UseCase\User\GetUserByEmail\GetUserByEmailInputData;

class GetUserByEmailTest extends TestCase
{
    public function test_execute_return_user(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);

        $userRepositoryMock->expects($this->once())
            ->method('getUserByEmail')
            ->willReturn(new User([
                1,
                'John Doe',
                'john@example.com',
                'password123',
                '2024-04-28 12:00:00',
                '2024-04-28 12:00:00',
                '2024-04-28 12:00:00'
            ]
        ));
        
        $getUserByEmail = new GetUserByEmail($userRepositoryMock);
        $inputData = new GetUserByEmailInputData('john@example.com');
        $result = $getUserByEmail->execute($inputData);
        
        $this->assertInstanceOf(User::class, $result);
    }

    public function test_execute_return_null_if_user_not_found(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);

        $userRepositoryMock->expects($this->once())
            ->method('getUserByEmail')
            ->willReturn(new User());

        $getUserByEmail = new GetUserByEmail($userRepositoryMock);

        $inputData = new GetUserByEmailInputData('nonexistent@example.com');

        $result = $getUserByEmail->execute($inputData);
        $this->assertInstanceOf(User::class, $result);
    }

}