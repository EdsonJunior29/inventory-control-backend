<?php

namespace App\Domain\UseCase\User\GetUserByEmail;

use App\Domain\IRepository\IUserRepository;
use App\Models\User as ModelsUser;

class GetUserByEmail
{

    protected $repo;

    public function __construct(IUserRepository $userRepository)
    {
       $this->repo = $userRepository;
    }

    public function execute(GetUserByEmailInputData $inputData) : ?ModelsUser
    {   
        return $this->repo->getUserByEmail($inputData->email);
    }
}