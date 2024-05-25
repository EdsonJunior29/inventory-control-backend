<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\CreateUser;

use App\Models\User;
use Tests\TestCase;
use App\Domain\IRepository\IUserRepository;
use App\Domain\UseCase\User\CreateUser\CreateUser;
use App\Domain\UseCase\User\CreateUser\CreateUserInputData;
use App\Enums\RoleType;
use App\Infra\User\UserRepository;
use App\Models\Role;

# php artisan test --filter=CreateUserTest
class CreateUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    # php artisan test --filter=CreateUserTest::test_execute_return_user
    public function test_execute_return_user(): void
    {
        $inputData = new CreateUserInputData('John Doe', 'john@example.com', 'password123', RoleType::ADMIN);

        $user = new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $userRepositoryMock = $this->createMock(IUserRepository::class);
        $userRepositoryMock->expects($this->once())
                           ->method('createUser')
                           ->willReturn($user);

        $createUser = new CreateUser($userRepositoryMock);
        $result = $createUser->execute($inputData);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
    }

    # php artisan test --filter=CreateUserTest::test_execute_return_user_null
    public function test_execute_return_user_null(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);

        $userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->willReturn(new User());

        $createUser = new CreateUser($userRepositoryMock);

        $inputData = new CreateUserInputData('John Doe', 'john@example.com',  'password123', RoleType::COLABS);
        
        $createUser->execute($inputData);
    }

}