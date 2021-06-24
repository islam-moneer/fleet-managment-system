<?php


namespace App\Backend\Interfaces\Auth;


use Illuminate\Http\Request;

interface RegisterContract
{

    public function register(Request $request);

}
