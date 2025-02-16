<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Application\UseCases\Auth\AuthUser\AuthUser;
use App\Domain\Exceptions\UnauthorizedUserException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

# php artisan test --filter=AuthControllerTest
class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('ProfilesAndUsersSeeder');
    }

    # php artisan test --filter=AuthControllerTest::test_login_success
    public function test_login_success()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        $response = $this->postJson(env('APP_URL').'/api/login', [
            'email' => $adminUser->email,
            'password' => 'Teste2@145',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'User' => [
                        'id', 'name', 'email',
                        'profiles' => [
                            '*' => [
                                'id', 'name',
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

      # php artisan test --filter=AuthControllerTest::test_login_return_user_not_found
      public function test_login_return_user_not_found()
      {        
          $useCaseMock = $this->getMockBuilder(AuthUser::class)
              ->disableOriginalConstructor()
              ->getMock();
          
          $useCaseMock->method('execute')
              ->willReturn([]);
          
          $this->app->instance(AuthUser::class, $useCaseMock);
  
          $response = $this->postJson(env('APP_URL').'/api/login', [
              'email' => 'admin@example2.com',
              'password' => 'Teste2@145dgygdywgyqd',
          ]);
  
          $response->assertStatus(Response::HTTP_NOT_FOUND)
              ->assertJson([
                  'message' => 'User Not Found',
                  'data' => [],
              ]
          );
      }

    # php artisan test --filter=AuthControllerTest::test_login_throw_unauthorized_user_exception
    public function test_login_throw_unauthorized_user_exception()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        
        $useCaseMock = $this->getMockBuilder(AuthUser::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $useCaseMock->method('execute')
            ->willThrowException(new UnauthorizedUserException('Credentials do not match', Response::HTTP_UNAUTHORIZED));

        $this->app->instance(AuthUser::class, $useCaseMock);

        $response = $this->postJson(env('APP_URL').'/api/login', [
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