<?php

namespace App\Application\UseCases\User\GetUserByEmail;

use App\Domain\IRepository\IUserRepository;
use App\Models\User as ModelsUser;
use App\Domain\Entities\User as EntitiesUser;

class GetUserByEmail
{

    protected $repo;

    public function __construct(IUserRepository $userRepository)
    {
       $this->repo = $userRepository;
    }

    public function execute(GetUserByEmailInputData $inputData) : ?ModelsUser
    {   
        $userEntity = new EntitiesUser(
            name: '',
            email: $inputData->email,
            password: ''
        );
        
        return $this->repo->getUserByEmail($userEntity);
    }
}