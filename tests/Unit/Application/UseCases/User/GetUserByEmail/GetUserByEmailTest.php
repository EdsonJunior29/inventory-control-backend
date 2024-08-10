<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\GetUserByEmail;

use App\Models\User;
use Tests\TestCase;
use App\Domain\IRepository\IUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmail;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmailInputData;

# php artisan test --testsuite=Unit --filter=GetUserByEmailTest
class GetUserByEmailTest extends TestCase
{
    use RefreshDatabase;

    # php artisan test --testsuite=Unit --filter=GetUserByEmailTest::test_execute_return_user
    public function test_execute_return_user(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);
        $email = "test@example.com";
        $inputData = new GetUserByEmailInputData($email);
        $user = new User([
            "name" => "John Doe",
            "email" => "test@example.com",
            "updated_at" => "2024-05-19T00:02:05.000000Z",
            "created_at" => "2024-05-19T00:02:05.000000Z",
            "id" => 1,
        ]);
        $userRepositoryMock->expects($this->once())
            ->method("getUserByEmail")
            ->with($email)
            ->willReturn($user);

        $getUserByEmail = new GetUserByEmail($userRepositoryMock);
        
        $result = $getUserByEmail->execute($inputData);
        
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user, $result);
    }

    # php artisan test --testsuite=Unit --filter=GetUserByEmailTest::test_execute_return_null_if_user_not_found
    public function test_execute_return_null_if_user_not_found(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method("getUserByEmail")
            ->willReturn(new User());

        $getUserByEmail = new GetUserByEmail($userRepositoryMock);
        $inputData = new GetUserByEmailInputData("nonexistent@example.com");
        
        $result = $getUserByEmail->execute($inputData);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(null, $result->email);
    }
}