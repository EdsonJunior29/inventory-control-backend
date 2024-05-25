<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Domain\Exception\UnauthorizedUserException;
use App\Domain\Services\UserServices\LoginUserService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('ProfilesAndUsersSeeder');
    }

    public function test_login_success()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        $response = $this->postJson(route('auth.login'), [
            'email' => $adminUser->email,
            'password' => 'Teste2@145',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'User' => [
                        'id', 'name', 'email', 'created_at', 'updated_at', 'deleted_at',
                        'profiles' => [
                            '*' => [
                                'id', 'name', 'created_at', 'updated_at', 'deleted_at',
                                'pivot' => [
                                    'users_id', 'profiles_id',
                                ],
                            ],
                        ],
                    ],
                    'token',
                ],
            ]
        );
    }

    public function test_login_throw_unauthorized_user_exception()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        $userServiceMock = $this->getMockBuilder(LoginUserService::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $userServiceMock->method('userLogin')
            ->willThrowException(new UnauthorizedUserException('Unauthorized', Response::HTTP_UNAUTHORIZED));

        $this->app->instance(LoginUserService::class, $userServiceMock);

        $response = $this->postJson(route('auth.login'), [
            'email' => $adminUser->email,
            'password' => 'Teste2@145dgygdywgyqd',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'status' => 'Error has occurred',
                'message' => 'Credentials do not match',
                'data' => '',
            ]
        );
    }    
}
