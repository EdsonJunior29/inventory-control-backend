<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Auth\AuthUser;

use App\Application\UseCases\Auth\AuthUser\AuthUser;
use App\Application\UseCases\Auth\AuthUser\AuthUserInputData;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmail;
use App\Domain\IRepository\IUserRepository;
use App\Domain\Exceptions\UnauthorizedUserException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

# php artisan test --filter=AuthUserTest
class AuthUserTest extends TestCase
{
    protected $authUser;
    protected $getUserByEmailMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getUserByEmailMock = Mockery::mock(GetUserByEmail::class);
        $this->authUser = new AuthUser(
            Mockery::mock(IUserRepository::class),
            $this->getUserByEmailMock
        );
    }

    # php artisan test --filter=AuthUserTest::test_authenticate_user_success
    public function test_authenticate_user_success()
    {
        $email = 'test@example.com';
        $password = 'password123';
        $hashedPassword = Hash::make($password);

        $user = new User(['name' => 'Test User', 'email' => $email, 'password' => $hashedPassword]);

        $this->getUserByEmailMock
            ->shouldReceive('execute')
            ->once()
            ->with(Mockery::on(function ($input) use ($email) {
                return $input->email === $email;
            }))
            ->andReturn($user);

        $inputData = new AuthUserInputData($email, $password);
        $result = $this->authUser->execute($inputData);

        $this->assertTrue(Hash::check('password123', $hashedPassword));
        $this->assertInstanceOf(User::class, $result);
    }

    # php artisan test --filter=AuthUserTest::test_authenticate_user_fails_throw_unauthorized_user_exception
    public function test_authenticate_user_fails_throw_unauthorized_user_exception()
    {
        $email = 'test@example.com';
        $hashedPassword = Hash::make('correctpassword');
        $password = 'wrongpassword';

        $user = new User(['name' => 'Test User', 'email' => $email, 'password' => $hashedPassword]);

        $this->getUserByEmailMock->shouldReceive('execute')
            ->once()
            ->with(Mockery::on(function ($input) use ($email) {
                return $input->email === $email;
            }))
            ->andReturn($user);

        $inputData = new AuthUserInputData($email, $password);

        $this->expectException(UnauthorizedUserException::class);
        $this->authUser->execute($inputData);
    }
}