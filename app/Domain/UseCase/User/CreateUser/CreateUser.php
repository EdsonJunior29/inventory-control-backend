<?php

namespace App\Domain\UseCase\User\CreateUser;

use App\Domain\IRepository\IUserRepository;
use App\Models\User;

class CreateUser
{
    protected $repo;

    public function __construct(IUserRepository $iUserRepository)
    {
        $this->repo = $iUserRepository;        
    }

    public function execute(CreateUserInputData $inputData): ?User
    {
        return $this->repo->createUser($inputData->name, $inputData->email, $inputData->password, $inputData->profileType);
    } 

}