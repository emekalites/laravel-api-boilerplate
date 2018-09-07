<?php

use Illuminate\Http\Request;

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

Route::namespace('Api\V1')->group(function () {
    Route::group(['middleware' => ['cors']], function() {
        Route::prefix('auth')->group(function () {
            Route::post('login', 'AuthController@login');
            Route::post('refresh', 'AuthController@refresh');
            Route::post('signup', 'AuthController@signUp');
        });

        Route::group(['middleware' => ['api', 'auth:api']], function() {
            Route::get('user', 'AuthController@user');
            Route::post('logout', 'AuthController@logout');

            // Users
            Route::get('users', 'UserController@index');            // get all
            Route::get('users/{id}', 'UserController@show');        // get one
        });

        Route::get('/',function(){
            return response()->json(['message' => 'Laravel API.']);
        });
    });
});
