<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginUserRequest;
use App\Http\Requests\AuthStoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use App\Domain\UseCase\User\GetUserByEmail\GetUserByEmail;
use App\Domain\UseCase\User\GetUserByEmail\GetUserByEmailInputData;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Infra\User\GetUser;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(AuthLoginUserRequest $request)
    {
        $request->validated($request->all());
    
        $getUserByEmailInputData = new GetUserByEmailInputData($request->email);
        $userCase = new GetUserByEmail(new GetUser());
        $user = $userCase->execute($getUserByEmailInputData);

        if(!$user || !Hash::check($request->password, $user->password)) 
        {
            return $this->error('', 'Credentials do not match', Response::HTTP_UNAUTHORIZED);
        }

        return $this->success([
            'User' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function store (AuthStoreUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

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
        return $this->success(Auth::user()->get(), 'success');
    }

}
