<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Api\Http\Middleware\UserAccessValid;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

# php artisan test --filter=UserControllerTest
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('ProfilesAndUsersSeeder');
    }

    public function authenticateUser()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);

        $response = $this->postJson(env('APP_URL').'/api/login', [
            'email' => $adminUser->email,
            'password' => 'Teste2@145',
        ]);

        return $response->json('data.token');
    }

    # php artisan test --filter=UserControllerTest::test_register_user_with_success
    public function test_register_user_with_success()
    {
        Profile::factory()->create();
        
        $token = $this->authenticateUser();

        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->postJson(env('APP_URL').'/api/users', [
                "name" => "John Test",
                "email" => "john.doe.test@example.com",
                "password" => "Teste2@145",
                "password_confirmation" => "Teste2@145",
            ]
        );
        
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
                'data' => [],
            ]
        );
    }

    # php artisan test --filter=UserControllerTest::test_update_user_with_success
    public function test_update_user_with_success()
    {
        Profile::factory()->create();
        
        $token = $this->authenticateUser();

        $userAccessValidMock = Mockery::mock(UserAccessValid::class);
        $userAccessValidMock->shouldReceive('handle')->andReturnUsing(function (Request $request, $next) {
            return $next($request);
        });
    
        $this->app->instance(UserAccessValid::class, $userAccessValidMock);

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->putJson(env('APP_URL').'/api/users/1', [
                "name" => "John Test",
                "email" => "john.doe.test@example.com",
            ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    protected function tearDown(): void
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        Mockery::close();
        parent::tearDown();
    }
}