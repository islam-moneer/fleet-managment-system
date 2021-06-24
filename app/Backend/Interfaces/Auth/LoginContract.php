<?php


namespace App\Backend\Interfaces\Auth;


use Illuminate\Http\Request;

interface LoginContract
{

    public function login(Request $request);

}
