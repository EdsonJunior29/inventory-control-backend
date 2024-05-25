<?php

namespace App\Domain\Services\UserServices;

use App\Domain\Exception\CreateUserException;
use App\Domain\Exception\UserProfilesNotFound;
use App\Domain\IRepository\IUserRepository;
use App\Domain\UseCase\User\CreateUser\CreateUser;
use App\Domain\UseCase\User\CreateUser\CreateUserInputData;
use App\Enums\RoleType;
use Illuminate\Support\Facades\Hash;
use App\Infra\User\UserRepository;
use App\Models\User;
use Exception;

class CreateUserService
{

    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;        
    }

    public function createUser(array $userData) : User
    {
        try {
            $userInputData = new CreateUserInputData(
                $userData['name'],
                $userData['email'],
                Hash::make($userData['password']),
                $this->validationRoleType($userData['role_name'])
            );

            $createUser = new CreateUser(new UserRepository());
            $user = $createUser->execute($userInputData);
        } catch (Exception $e) {
            throw new CreateUserException($e->getMessage());
        } catch (UserProfilesNotFound $userProfilesNotFound) {
            throw $userProfilesNotFound;
        }

        return $user;
    }

    public function validationRoleType(string $roleName): RoleType
    {
        switch ($roleName) {
            case 'Admin':
                return RoleType::ADMIN;
                break;
            case 'Colabs':
                return RoleType::COLABS;
                break;
            case 'Colabs':
                return RoleType::CLIENT;
                break;
            default:
                throw new UserProfilesNotFound();
                break;
        }
    }
}