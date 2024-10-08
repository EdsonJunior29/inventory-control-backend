<?php

namespace App\Application\UseCases\User\CreateUser;

use App\Enums\Profiles;
use App\Domain\Entities\User as EntitiesUser;
use App\Domain\IRepository\IUserRepository;
use App\Exceptions\CreateUserException;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    protected $repo;

    public function __construct(IUserRepository $iUserRepository)
    {
        $this->repo = $iUserRepository;        
    }

    public function execute(CreateUserInputData $inputData): void
    {
        $user = new EntitiesUser(
            $inputData->name,
            $inputData->email,
            $this->generatePasswordHash($inputData->password)
        );

        try {
            $this->repo->createUser($user, Profiles::CLIENT);
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