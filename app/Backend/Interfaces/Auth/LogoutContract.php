<?php


namespace App\Backend\Interfaces\Auth;


use Illuminate\Http\Request;

interface LogoutContract
{

    public function logout(Request $request);

}
