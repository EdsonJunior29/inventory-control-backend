<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Services;

use App\Domain\Exception\UnauthorizedUserException;
use App\Domain\IRepository\IUserRepository;
use App\Domain\Services\UserServices\LoginUserService;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

# php artisan test --filter=LoginUserServiceTest
class LoginUserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('RolesAndUsersSeeder');
    }

    # php artisan test --filter=LoginUserServiceTest::test_userLogin_returns_user_on_success
    public function test_userLogin_returns_user_on_success()
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);

        $userRepositoryMock
            ->method('getUserByEmail')
            ->with('admin@example.com')
            ->willReturn(new User([
                'email' => 'admin@example.com',
                'password' => Hash::make('Teste2@145'),
            ]));
    
        $loginService = new LoginUserService($userRepositoryMock);
    
        $loginData = [
            'email' => 'admin@example.com',
            'password' => 'Teste2@145',
        ];
    
        $loggedInUser = $loginService->userLogin($loginData);
    
        $this->assertInstanceOf(User::class, $loggedInUser);
        $this->assertEquals($loginData['email'], $loggedInUser->email);
        $this->assertTrue(Hash::check($loginData['password'], $loggedInUser->password));
    }

    # php artisan test --filter=LoginUserServiceTest::test_user_login_throws_unauthorized_user_exception_if_password_incorrect
    public function test_user_login_throws_unauthorized_user_exception_if_password_incorrect(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);
        
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123444',
        ];

        $loginService = new LoginUserService($userRepositoryMock);

        $this->expectException(UnauthorizedUserException::class);
        $loginService->userLogin($loginData);
    }

    # php artisan test --filter=LoginUserServiceTest::test_user_login_throws_unauthorized_user_exception_if_user_is_null
    public function test_user_login_throws_unauthorized_user_exception_if_user_is_null(): void
    {
        $userRepositoryMock = $this->createMock(IUserRepository::class);
        
        $loginData = [
            'email' => 'test@examplenoaply.com',
            'password' => 'password123',
        ];

        $loginService = new LoginUserService($userRepositoryMock);

        $this->expectException(UnauthorizedUserException::class);
        $loginService->userLogin($loginData);
    }
}