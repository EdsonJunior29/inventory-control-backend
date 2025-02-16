<?php

namespace App\Api\Http\Controllers;

use App\Application\UseCases\User\CreateUser\CreateUser;
use App\Application\UseCases\User\CreateUser\CreateUserInputData;
use App\Domain\Exceptions\CreateUserException;
use App\Api\Http\Requests\AuthStoreUserRequest;
use App\Api\Http\Requests\UpdateUserRequest;
use App\Api\Traits\HttpResponses;
use App\Application\UseCases\User\UpdateUser\UpdateUserInputData;
use App\Application\UseCases\User\UpdateUser\UpdateUser;
use App\Domain\Exceptions\UpdateUserException;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use HttpResponses;

    private CreateUser $createUserUseCase;
    private UpdateUser $updateUserUseCase;

    public function __construct(CreateUser $createUserUseCase, UpdateUser $updateUserUseCase)
    {
        $this->createUserUseCase = $createUserUseCase;
        $this->updateUserUseCase = $updateUserUseCase;
    }

    public function store(AuthStoreUserRequest $request)
    {
        $request->validated($request->all());

        try {
            $createUserInputData = new CreateUserInputData(
                $request['name'],
                $request['email'],
                $request['password']
            );
            
            $this->createUserUseCase->execute($createUserInputData);
        } catch (CreateUserException $e) {
            return $this->error(
                [],
                $e->getMessage(),
                $e->getCode()
            );
        }
       
        return $this->success(
            [],
            'User created successfully',
            Response::HTTP_CREATED
        );
    }

    public function update(int $id, UpdateUserRequest $request)
    {
        $request->validated($request->all());
        
        try {
            $updateUserInputData = new UpdateUserInputData(
                $id,
                $request['name'],
                $request['email']
            );
            
            $this->updateUserUseCase->execute($updateUserInputData);
        } catch (UpdateUserException $e) {
            return $this->error(
                [],
                $e->getMessage(),
                $e->getCode()
            );
        }

        return $this->updated(
            [],
            'User updated successfully',
            Response::HTTP_NO_CONTENT
        );
    }
}