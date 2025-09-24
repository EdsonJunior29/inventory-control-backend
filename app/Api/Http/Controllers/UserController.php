<?php

namespace App\Api\Http\Controllers;

use App\Application\UseCases\User\CreateUser\CreateUserInputData;
use App\Domain\Exceptions\CreateUserException;
use App\Api\Http\Requests\AuthStoreUserRequest;
use App\Api\Http\Requests\UpdateUserRequest;
use App\Api\Traits\HttpResponses;
use App\Application\Contracts\User\ICreateUser;
use App\Application\Contracts\User\IUpdateUser;
use App\Application\UseCases\User\UpdateUser\UpdateUserInputData;
use App\Domain\Exceptions\UpdateUserException;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use HttpResponses;

    private ICreateUser $iCreateUser;
    private IUpdateUser $iUpdateUser;

    public function __construct(ICreateUser $iCreateUserUseCase, IUpdateUser $iUpdateUser)
    {
        $this->iCreateUser = $iCreateUserUseCase;
        $this->iUpdateUser = $iUpdateUser;
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
            
            $this->iCreateUser->execute($createUserInputData);
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
            
            $this->iUpdateUser->execute($updateUserInputData);
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