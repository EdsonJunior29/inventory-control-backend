<?php

namespace App\Api\Http\Middleware;

use App\Api\Traits\HttpResponses;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmail;
use App\Application\UseCases\User\GetUserByEmail\GetUserByEmailInputData;
use App\Domain\Enums\Profiles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccessValid
{
    use HttpResponses;

    protected $getUserByEmail;

    public function __construct(GetUserByEmail $getUserByEmail)
    {
        $this->getUserByEmail = $getUserByEmail;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->has('email')) {
            return $this->badRequest(
                'Email is required',
            );
        }

        $getUserByEmail = app(GetUserByEmail::class);
        $user = $getUserByEmail->execute(new GetUserByEmailInputData($request->email));

        if ($user->profiles->first()->id !== Profiles::ADMIN->value) {
            return $this->unauthorized(
                data :  'Unauthorized',
                message : 'You do not have access to this resource',
                code : Response::HTTP_UNAUTHORIZED
            );
        }
        
        return $next($request);
    }
}