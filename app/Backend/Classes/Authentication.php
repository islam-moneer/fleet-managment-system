<?php

namespace App\Backend\Classes;

use App\Models\User;
use App\Backend\Helpers\Constant;
use Laravel\Sanctum\NewAccessToken;

class Authentication
{
    public function createToken(User $user): NewAccessToken
    {
        return $user->createToken(Constant::TOKEN_NAME);
    }
}