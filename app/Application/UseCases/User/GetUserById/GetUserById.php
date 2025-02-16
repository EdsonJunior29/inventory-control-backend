<?php

namespace App\Application\UseCases\User\GetUserById;

use App\Domain\IRepository\IUserRepository;

class GetUserById
{
    protected $repo;

    public function __construct(IUserRepository $userRepository)
    {
       $this->repo = $userRepository;
    }

    public function execute(GetUserInputData $inputData)
    {
        return $this->repo->getUserById($inputData->id);
    }
}