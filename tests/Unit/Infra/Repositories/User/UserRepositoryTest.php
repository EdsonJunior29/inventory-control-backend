<?php

namespace Tests\Unit\Infra\Repositories\User;

use App\Domain\Entities\User as EntitiesUser;
use App\Domain\Enums\Profiles;
use App\Domain\Exceptions\UserNotFoundException;
use App\Infra\Repositories\User\UserRepository;
use App\Models\User;
use Database\Seeders\ProfilesAndUsersSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

# php artisan test --filter=UserRepositoryTest
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    # php artisan test --filter=UserRepositoryTest::test_getUserById_returns_user_when_exists
    public function teste_getUserById_returns_user_when_exists()
    {
        $user = User::factory()->create();

        $result = $this->repository->getUserById($user->id);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }

    # php artisan test --filter=UserRepositoryTest::test_getUserById_returns_null_when_not_exists
    public function test_getUserById_returns_null_when_not_exists()
    {
        $result = $this->repository->getUserById(9999);

        $this->assertNull($result);
    }

    # php artisan test --filter=UserRepositoryTest::test_create_user_creates_user_with_profile
    public function test_create_user_creates_user_with_profile()
    {
        //Utilizando o seeder para criar o perfil Admin e o Usuário
        $this->seed(ProfilesAndUsersSeeder::class);

        $entityUser = new EntitiesUser();
        $entityUser->setName('Novo Usuário');
        $entityUser->setEmail('novo@email.com');
        $entityUser->setPassword(bcrypt('senha123'));

        $this->repository->createUser($entityUser, Profiles::ADMIN);

        $this->assertDatabaseHas('users', [
            'name' => 'Novo Usuário',
            'email' => 'novo@email.com'
        ]);

        $user = User::where('email', 'novo@email.com')->first();
        $this->assertTrue($user->profiles->contains(Profiles::ADMIN->value));
    }

    # php artisan test --filter=UserRepositoryTest::test_getUserByEmail_returns_null_when_not_exists
    public function test_getUserByEmail_returns_null_when_not_exists()
    {
        $entityUser = new EntitiesUser();
        $entityUser->setName('Inexistente');
        $entityUser->setEmail('inexistente@email.com');
        $entityUser->setPassword(bcrypt('senha123'));

        $result = $this->repository->getUserByEmail($entityUser);

        $this->assertNull($result);
    }

    # php artisan test --filter=UserRepositoryTest::test_updateUser_updates_user_data
    public function test_updateUser_updates_user_data()
    {
        $user = User::factory()->create([
            'name' => 'Nome Antigo',
            'email' => 'antigo@email.com'
        ]);

        $entityUser = new EntitiesUser();
        $entityUser->setId($user->id);
        $entityUser->setName('Nome Novo');
        $entityUser->setEmail('novo@email.com');
        $entityUser->setPassword(bcrypt($user->password));

        $this->repository->updateUser($entityUser);

        $updatedUser = User::find($user->id);
        $this->assertEquals('Nome Novo', $updatedUser->name);
        $this->assertEquals('novo@email.com', $updatedUser->email);
    }

    # php artisan test --filter=UserRepositoryTest::test_updateUser_throws_exception_when_user_not_found
    public function test_updateUser_throws_exception_when_user_not_found()
    {
        $this->expectException(UserNotFoundException::class);

        $entityUser = new EntitiesUser();
        $entityUser->setId(999);
        $entityUser->setName('Inexistente');
        $entityUser->setEmail('inexistente@email.com');
        $entityUser->setPassword(bcrypt('senha123'));

        $this->repository->updateUser($entityUser);
    }
}