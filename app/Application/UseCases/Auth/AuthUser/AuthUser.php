<?php

namespace App\Application\UseCases\Auth\AuthUser;

use App\Domain\IRepository\IUserRepository;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmail;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmailInputData;
use App\Exceptions\UnauthorizedUserException;
use Illuminate\Support\Facades\Hash;

class AuthUser
{
    protected $iUserRepository;
    protected $getUserByEmail;

    public function __construct(IUserRepository $iUserRepository, GetUserByEmail $getUserByEmail)
    {
        $this->iUserRepository = $iUserRepository;
        $this->getUserByEmail = $getUserByEmail;       
    }

    public function execute(AuthUserInputData $inputData)
    {
        $getUserByEmailInputData = new GetUserByEmailInputData($inputData->email);
        $user = $this->getUserByEmail->execute($getUserByEmailInputData);

        if(!Hash::check($inputData->password, $user->password)) {
            throw new UnauthorizedUserException();
        }

        return $user;
    }
}