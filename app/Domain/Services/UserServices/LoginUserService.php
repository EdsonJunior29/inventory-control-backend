<?php

namespace App\Domain\Services\UserServices;

use App\Domain\Exception\UnauthorizedUserException;
use App\Models\User;
use App\Infra\User\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Domain\IRepository\IUserRepository;
use App\Domain\UseCase\User\GetUserByEmail\GetUserByEmail;
use App\Domain\UseCase\User\GetUserByEmail\GetUserByEmailInputData;

class LoginUserService
{

    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function userLogin(array $userData) : User
    {
        $getUserByEmailInputData = new GetUserByEmailInputData($userData['email']);
        $userCase = new GetUserByEmail(new UserRepository());
        $user = $userCase->execute($getUserByEmailInputData);

        if(!$user || !Hash::check($userData['password'], $user->password)) {
            throw new UnauthorizedUserException();
        }

        return $user;
    }
}