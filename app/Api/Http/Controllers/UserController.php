<?php

namespace App\Api\Http\Controllers;

use App\Application\UseCases\User\CreateUser\CreateUser;
use App\Application\UseCases\User\CreateUser\CreateUserInputData;
use App\Domain\Exceptions\CreateUserException;
use App\Api\Http\Requests\AuthStoreUserRequest;
use App\Api\Traits\HttpResponses;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use HttpResponses;

    private CreateUser $createUserUseCase;

    public function __construct(CreateUser $createUserUseCase)
    {
        $this->createUserUseCase = $createUserUseCase;
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
}