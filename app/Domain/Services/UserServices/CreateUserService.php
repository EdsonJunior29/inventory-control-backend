<?php

namespace App\Domain\Services\UserServices;

use App\Domain\Exception\CreateUserException;
use App\Domain\Exception\UserProfilesNotFound;
use App\Domain\IRepository\IUserRepository;
use App\Domain\UseCase\User\CreateUser\CreateUser;
use App\Domain\UseCase\User\CreateUser\CreateUserInputData;
use App\Enums\Profiles;
use Illuminate\Support\Facades\Hash;
use App\Infra\User\UserRepository;
use App\Models\User;
use Exception;
use Throwable;

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
                $this->validationProfileType($userData['profile_name'])
            );

            $createUser = new CreateUser(new UserRepository());
            $user = $createUser->execute($userInputData);
        } catch (Throwable $e) {
            throw new CreateUserException($e->getMessage());
        }

        return $user;
    }

    public function validationProfileType(string $profileName): Profiles
    {
        switch ($profileName) {
            case 'Admin':
                return Profiles::ADMIN;
                break;
            case 'Colabs':
                return Profiles::COLABS;
                break;
            case 'Colabs':
                return Profiles::CLIENT;
                break;
            default:
                throw new UserProfilesNotFound();
                break;
        }
    }
}