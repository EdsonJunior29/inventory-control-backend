<?php

namespace App\Http\Controllers;

use App\Domain\Exception\CreateUserException;
use App\Domain\Exception\UnauthorizedUserException;
use App\Domain\Services\UserServices\CreateUserService;
use App\Domain\Services\UserServices\LoginUserService;
use App\Http\Requests\AuthLoginUserRequest;
use App\Http\Requests\AuthStoreUserRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Infra\User\UserRepository;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(AuthLoginUserRequest $request)
    {
        $request->validated($request->all());

        try {    
            $userService = new LoginUserService(new UserRepository());
            $user = $userService->userLogin($request->all());
        } catch (UnauthorizedUserException $ex) {
            return $this->error('', $ex->getMessage(), $ex->getCode());
        }
       
        return $this->success([
            'User' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function store (AuthStoreUserRequest $request)
    {
        $request->validated($request->all());

        try {
            $userService = new CreateUserService(new UserRepository());
            $user = $userService->createUser($request->all());
        } catch (CreateUserException $ex) {
            return $this->error('', $ex->getMessage(), $ex->getCode());
        }
     
        return $this->success(['User' => $user], 'User created successfully',  Response::HTTP_CREATED);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success(
            '',
            'You have successfuly been logged out and your token has been deleted.',
            Response::HTTP_NO_CONTENT
        );
    }

    public function me()
    {
        $user = Auth::user();

        if(!$user) {
            return $this->error('', 'User not found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($user, 'Success');
    }

}
