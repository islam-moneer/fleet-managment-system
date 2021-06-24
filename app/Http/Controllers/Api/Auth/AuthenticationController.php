<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Backend\API\UserAuthentication;
use App\Http\Requests\Api\RegisterRequest;

class AuthenticationController extends Controller
{
    private $user;

    /**
     * AuthenticationController constructor.
     * @param UserAuthentication $user
     */
    public function __construct(UserAuthentication $user)
    {
        $this->user = $user;
    }

    public function register(RegisterRequest $request)
    {
        list($user, $token) = $this->user->register($request);

        return UserResource::make($user)->token($token->plainTextToken);
    }
}
