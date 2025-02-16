<?php

namespace Tests\Feature\Http\Api\Middleware;

use App\Api\Http\Middleware\UserAccessValid;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmail;
use App\Domain\Enums\Profiles;
use Illuminate\Http\Request;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

# php artisan test --filter=UserAccessValidMiddlewareTest
class UserAccessValidMiddlewareTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('ProfilesAndUsersSeeder');
    }


    # php artisan test --filter=UserAccessValidMiddlewareTest::test_email_is_required_throw_not_found_user_exception
    //public function test_email_is_required_throw_not_found_user_exception()
    //{
    //    $middleware = new UserAccessValid(Mockery::mock(GetUserByEmail::class));
//
    //    $request = Request::create('/', 'GET');
//
    //    $response = $middleware->handle($request, function () {
    //        return response('next');
    //    });
//
    //    $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    //    $this->assertJsonStringEqualsJsonString(
    //        json_encode(['message' => 'Email is required']),
    //        $response->getContent()
    //    );
    //}

    # php artisan test --filter=UserAccessValidMiddlewareTest::test_user_is_not_admin_throw_user_unauthorized_exception
    //public function test_user_is_not_admin_throw_user_unauthorized_exception()
    //{
    //    $userMock = Mockery::mock();
    //    $userMock->profiles = collect([Mockery::mock()->shouldReceive('getId')->andReturn(Profiles::ADMIN)->getMock()]);
//
    //    $getUserByEmailMock = Mockery::mock(GetUserByEmail::class);
    //    $getUserByEmailMock->shouldReceive('execute')->andReturn($userMock);
//
    //    $middleware = new UserAccessValid($getUserByEmailMock);
//
    //    $request = Request::create('/api/users', 'POST', ['email' => 'admin@example.com']);
//
    //    $response = $middleware->handle($request, function () {
    //        return response('next');
    //    });
//
    //    // Verifica se o middleware permitiu a passagem
    //    $this->assertEquals('next', $response->getContent());
    //}
}