<?php

namespace App\Domain\Services\UserServices;

use App\Domain\Exception\CreateUserException;
use App\Domain\IRepository\IUserRepository;
use App\Domain\UseCase\User\CreateUser\CreateUser;
use App\Domain\UseCase\User\CreateUser\CreateUserInputData;
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
            $userInputData = new CreateUserInputData($userData['name'], $userData['email'],  Hash::make($userData['password']));
            $createUser = new CreateUser(new UserRepository());
            $user = $createUser->execute($userInputData);
        } catch (Exception $e) {
            throw new CreateUserException($e->getMessage());
        }

        return $user;
    }
}