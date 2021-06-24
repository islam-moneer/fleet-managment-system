<?php


namespace App\Backend\API;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Backend\Classes\Authentication;
use App\Backend\Exceptions\DefaultException;
use App\Backend\Repositories\UserRepository;
use App\Backend\Interfaces\Auth\LoginContract;
use App\Backend\Interfaces\Auth\LogoutContract;
use App\Backend\Interfaces\Auth\RegisterContract;

class UserAuthentication implements RegisterContract, LoginContract, LogoutContract
{
    private $userRepo;
    private $authRepo;

    /**
     * ClientAuthentication constructor.
     * @param UserRepository $userRepo
     * @param Authentication $authenticationRepo
     */
    public function __construct(UserRepository $userRepo, Authentication $authenticationRepo)
    {
        $this->userRepo = $userRepo;
        $this->authRepo = $authenticationRepo;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function register(Request $request): array
    {

        // create new user
        $user = $this->userRepo->create($request);

        // generate new access token
        $token = $this->authRepo->createToken($user);

        return [$user, $token];
    }

    /**
     * @param Request $request
     * @return array
     * @throws DefaultException
     */
    public function login(Request $request): array
    {
        // attempt login
        $authenticated = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$authenticated) {

            throw new DefaultException(__('messages.invalid_credentials'), Response::HTTP_UNAUTHORIZED);

        }

        // get authenticated user
        $user = $request->user();

        // generate new access token
        $token = $this->authRepo->createToken($user);

        return [$user, $token];
    }

    /**
     * @param Request $request
     */
    public function logout(Request $request): void
    {
        // get authenticated user
        $user = $request->user();

        // revoke user token
        $this->authRepo->revokeToken($user);
    }

}