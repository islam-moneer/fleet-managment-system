<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api\Auth', 'middleware' => 'guest:sanctum'], function () {

        Route::post('register', 'AuthenticationController@register');
        Route::post('login', 'AuthenticationController@login');

});

Route::group(['namespace' => 'Api', 'middleware' => ['auth:sanctum']], function () {
    Route::post('logout', 'Auth\AuthenticationController@logout');

    Route::get('trips', 'TripController@index');
    Route::get('available-seats/{trip}/{from}/{to}', 'SeatController@available');
    Route::post('book-seat', 'SeatController@book');
});