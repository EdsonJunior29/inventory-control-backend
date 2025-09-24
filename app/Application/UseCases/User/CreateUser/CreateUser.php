<?php

namespace App\Application\UseCases\User\CreateUser;

use App\Application\Contracts\User\ICreateUser;
use App\Domain\Enums\Profiles;
use App\Domain\Entities\User as EntitiesUser;
use App\Domain\Exceptions\CreateUserException;
use App\Domain\IRepository\IUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser implements ICreateUser
{
    protected $repo;

    public function __construct(IUserRepository $iUserRepository)
    {
        $this->repo = $iUserRepository;        
    }

    public function execute(CreateUserInputData $inputData): User
    {
        $user = new EntitiesUser();

        $user->setName($inputData->name);
        $user->setEmail($inputData->email);
        $user->setPassword($this->generatePasswordHash($inputData->password));

        try {
            return $this->repo->createUser($user, Profiles::CLIENT);
        } catch (\Throwable $th) {
            throw new CreateUserException(
                $th->getMessage(),
                $th->getCode()
            ); 
        }
    } 

    private function generatePasswordHash(string $password): string
    {
        return Hash::make($password);
    }

}