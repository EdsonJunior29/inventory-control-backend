<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\User\CreateUser;

use App\Application\UseCases\User\CreateUser\CreateUser;
use App\Application\UseCases\User\CreateUser\CreateUserInputData;
use App\Domain\Entities\User as EntitiesUser;
use App\Domain\IRepository\IUserRepository;
use Mockery;
use Tests\TestCase;
use App\Domain\Enums\Profiles as EnumsProfiles;
use App\Domain\Exceptions\CreateUserException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

# php artisan test --filter=CreateUserTest
class CreateUserTest extends TestCase
{
    # php artisan test --filter=CreateUserTest::test_execute_save_user_with_default_profile
    public function test_execute_save_user_with_default_profile()
    {
        $createUserInputData = new CreateUserInputData(
            'John Doe',
            'john@example.com',
            'password123'
        );
    
        $mockUserModel = Mockery::mock(User::class);
        $mockUserModel->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $mockUserModel->shouldReceive('getAttribute')->with('name')->andReturn('John Doe');
        $mockUserModel->shouldReceive('getAttribute')->with('email')->andReturn('john@example.com');
        
        $iUserRepository = Mockery::mock(IUserRepository::class);
        $iUserRepository
            ->shouldReceive('createUser')
            ->once()
            ->with(Mockery::on(function (EntitiesUser $user) use ($createUserInputData) {
                return $user->getName() === $createUserInputData->name &&
                       $user->getEmail() === $createUserInputData->email &&
                       Hash::check($createUserInputData->password, $user->getPassword());
            }), EnumsProfiles::CLIENT)
            ->andReturn($mockUserModel);
    
        $createUser = new CreateUser($iUserRepository);
        $result = $createUser->execute($createUserInputData);
        
        $this->assertInstanceOf(User::class, $result);
    }

    # php artisan test --filter=CreateUserTest::test_execute_save_user_with_default_profile_throws_exception_on_failure
    public function test_execute_save_user_with_default_profile_throws_exception_on_failure()
    {
        $createUserInputData = new CreateUserInputData(
            'John Doe',
            'john@example.com',
            'password123'
        );

        $iUserRepository = Mockery::mock(IUserRepository::class);
        $iUserRepository->shouldReceive('createUser')
            ->once()
            ->with(Mockery::type(EntitiesUser::class), EnumsProfiles::CLIENT)
            ->andThrow(new \Exception('Database error'));

        $createUser = new CreateUser($iUserRepository);
        
        $this->expectException(CreateUserException::class);
        $this->expectExceptionMessage('Database error');

        $createUser->execute($createUserInputData);
    }

    # php artisan test --filter=CreateUserTest::test_generate_password_hash
    public function test_generate_password_hash()
    {
        $reflector = new \ReflectionClass(CreateUser::class);
        $method = $reflector->getMethod('generatePasswordHash');
        $method->setAccessible(true);

        $createUser = new CreateUser(Mockery::mock(IUserRepository::class));
        $hashedPassword = $method->invoke($createUser, 'password123');

        $this->assertTrue(Hash::check('password123', $hashedPassword));
    }
}