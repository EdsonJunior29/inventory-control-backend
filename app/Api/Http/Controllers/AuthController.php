<?php

namespace App\Api\Http\Controllers;

use App\Application\UseCases\Auth\AuthUser\AuthUser;
use App\Application\UseCases\Auth\AuthUser\AuthUserInputData;
use App\Domain\Exceptions\UnauthorizedUserException;
use App\API\Http\Requests\AuthLoginUserRequest;
use App\Api\Traits\HttpResponses;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    private $authUserUseCase;

    public function __construct(AuthUser $authUser)
    {
        $this->authUserUseCase = $authUser;
    }

    public function login(AuthLoginUserRequest $request)
    {
        $request->validated($request->all());

        try {    
            $userInputData = new AuthUserInputData(
                $request['email'],
                $request['password']
            );
            $user = $this->authUserUseCase->execute($userInputData);

            if(!$user) {
                return $this->success(
                    [],
                    'User Not Found',
                    Response::HTTP_NOT_FOUND
                );
            }

        } catch (UnauthorizedUserException $ex) {
            return $this->error('', $ex->getMessage(), $ex->getCode());
        }
       
        return $this->success([
            'User' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
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