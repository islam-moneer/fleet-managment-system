<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Backend\API\UserAuthentication;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Backend\Exceptions\DefaultException;

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

    /**
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource
    {
        list($user, $token) = $this->user->register($request);

        return UserResource::make($user)->token($token->plainTextToken);
    }

    /**
     * @param LoginRequest $request
     * @return UserResource
     * @throws DefaultException
     */
    public function login(LoginRequest $request): UserResource
    {
        list($user, $token) = $this->user->login($request);

        return UserResource::make($user)->token($token->plainTextToken);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->user->logout($request);

        return response()->json([
            'message' => __('messages.user_logged_out')
        ]);
    }
}
