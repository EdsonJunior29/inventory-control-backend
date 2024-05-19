<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Services;

use Exception;
use Tests\TestCase;
use App\Models\User;
use App\Infra\User\UserRepository;
use App\Domain\Exception\CreateUserException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Services\UserServices\CreateUserService;

# php artisan test --filter=CreateUserServiceTest
class CreateUserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan("migrate");
    }

    # php artisan test --filter=CreateUserServiceTest::test_createUser_successfully
    public function test_createUser_successfully()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);

        $userRepositoryMock
            ->method("createUser")
            ->willReturn(new User([
                "name"=> "John Doe",
                "email" => "john@example.com",
                "updated_at" => "2024-05-19T00:02:05.000000Z",
                "created_at" => "2024-05-19T00:02:05.000000Z",
                "id" => 1,
            ])
        );

        $userService = new CreateUserService($userRepositoryMock);

        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "password123"
        ];

        $user = $userService->createUser($userData);
        
        $this->assertEquals(1, $user->id);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }
    
    # php artisan test --filter=CreateUserServiceTest::test_createUser_throws_exception_on_failure
    public function test_createUser_throws_exception_on_failure(): void
    {
       $userRepositoryMock = $this->createMock(UserRepository::class);

       $userRepositoryMock
            ->method("createUser")
            ->willThrowException(new Exception("Error creating user"));

       $userService = new CreateUserService($userRepositoryMock);

       $userData = [
           "name" => "John Doe",
           "email" => "john@example.com",
       ];

       $this->expectException(CreateUserException::class);

       $userService->createUser($userData);
    }
}