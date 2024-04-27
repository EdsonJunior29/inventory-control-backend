<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginUserRequest;
use App\Http\Requests\AuthStoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(AuthLoginUserRequest $request)
    {
        $request->validated($request->all());

        $credencials = [
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        if(!Auth::attempt($request->only($credencials))) 
        {
            return $this->error('', 'Credentials do not match', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->email)->first();

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

}
