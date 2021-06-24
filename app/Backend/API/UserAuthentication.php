<?php


namespace App\Backend\API;


use Illuminate\Http\Request;
use App\Backend\Classes\Authentication;
use App\Backend\Repositories\UserRepository;
use App\Backend\Interfaces\Auth\RegisterContract;

class UserAuthentication implements RegisterContract
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


}