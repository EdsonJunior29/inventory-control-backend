<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\User\GetUserById;

use App\Application\UseCases\User\GetUserById\GetUserById;
use App\Application\UseCases\User\GetUserById\GetUserInputData;
use App\Domain\IRepository\IUserRepository;
use App\Models\User;
use Mockery;
use Tests\TestCase;

# php artisan test --filter=GetUserByIdTest
class GetUserByIdTest extends TestCase
{
    # php artisan test --filter=GetUserByIdTest::test_execute_get_user_by_id
    public function test_execute_get_user_by_id()
    {
        $getUserByIdInputData = new GetUserInputData(1);

        $iUserRepository = Mockery::mock(IUserRepository::class);
        $iUserRepository
            ->shouldReceive('getUserById')
            ->once()
            ->with(Mockery::on(function (int $id) use ($getUserByIdInputData) {
                return $id === $getUserByIdInputData->id;
            }))
            ->andReturn(new User(['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com']));
        
        $getUserById = new GetUserById($iUserRepository);
        $getUserById->execute($getUserByIdInputData);
    }
}