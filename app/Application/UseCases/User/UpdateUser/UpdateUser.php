<?php

namespace App\Application\UseCases\User\UpdateUser;

use App\Application\Contracts\User\IUpdateUser;
use App\Application\UseCases\User\UpdateUser\UpdateUserInputData;
use App\Domain\Entities\User;
use App\Domain\Exceptions\UpdateUserException;
use App\Domain\IRepository\IUserRepository;

class UpdateUser implements IUpdateUser
{
    protected $repo;

    public function __construct(IUserRepository $userRepository)
    {
       $this->repo = $userRepository;
    }

    public function execute(UpdateUserInputData $updateUserInputData)
    {
        $userEntity = new User();
        $userEntity->setId($updateUserInputData->id);
        $userEntity->setName($updateUserInputData->name);
        $userEntity->setEmail($updateUserInputData->email);

        try {
            return $this->repo->updateUser($userEntity);
        } catch (\Throwable $th) {
            throw new UpdateUserException(
                $th->getMessage(),
                $th->getCode()
            );
        }
    }
}