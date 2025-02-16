<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\User\UpdateUser;

use App\Application\UseCases\User\UpdateUser\UpdateUser;
use App\Application\UseCases\User\UpdateUser\UpdateUserInputData;
use App\Domain\IRepository\IUserRepository;
use App\Domain\Entities\User as EntitiesUser;
use App\Domain\Exceptions\UpdateUserException;
use Mockery;
use Tests\TestCase;

# php artisan test --filter=UpdateUserTest
class UpdateUserTest extends TestCase
{
    # php artisan test --filter=UpdateUserTest::test_execute_update_user
    public function test_execute_update_user()
    {
        $updateUserInputData = new UpdateUserInputData(
            1,
            'John Doe',
            'john@example.com'
        );

        $iUserRepository = Mockery::mock(IUserRepository::class);
        $iUserRepository
            ->shouldReceive('updateUser')
            ->once()
            ->with(Mockery::on(function (EntitiesUser $user) use ($updateUserInputData) {
                return $user->getId() === $updateUserInputData->id &&
                       $user->getName() === $updateUserInputData->name &&
                       $user->getEmail() === $updateUserInputData->email;
            }));
        
        $updateUser = new UpdateUser($iUserRepository);
        $updateUser->execute($updateUserInputData);
    }

    # php artisan test --filter=UpdateUserTest::test_execute_update_user_throws_update_user_exception
    public function test_execute_update_user_throws_update_user_exception()
    {
        $updateUserInputData = new UpdateUserInputData(
            1,
            'John Doe',
            'john@example.com'
        );

        $iUserRepository = Mockery::mock(IUserRepository::class);
        $iUserRepository
            ->shouldReceive('updateUser')
            ->once()
            ->with(Mockery::on(function (EntitiesUser $user) use ($updateUserInputData) {
                return $user->getId() === $updateUserInputData->id &&
                       $user->getName() === $updateUserInputData->name &&
                       $user->getEmail() === $updateUserInputData->email;
            }))
            ->andThrow(new UpdateUserException());
        
        $updateUser = new UpdateUser($iUserRepository);
        $this->expectException(UpdateUserException::class);
        $this->expectExceptionMessage('Error updeted user');

        $updateUser->execute($updateUserInputData);
    }
}