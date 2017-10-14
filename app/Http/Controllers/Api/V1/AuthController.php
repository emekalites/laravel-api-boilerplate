<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    /**
     * API Login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_email_or_password'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'), 200);
    }

    /**
     * API Refresh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();
        if(!$token){
            return response()->json(['error' => 'token_not_found'], 500);
        }

        try{
            $token = JWTAuth::refresh($token);
            return response()->json(compact('token'), 200);
        } catch(TokenInvalidException $e){
            return response()->json(['error' => 'invalid_token'], 500);
        }
    }

    /**
     * API User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request){
        $user = JWTAuth::toUser($request->token);
        return response()->json($user, 200);
    }

    /**
     * API SignUp
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=> $validator->errors()], 500);
        }

        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $user->roles()->attach(Role::where('name', 'admin')->first());
            return response()->json(['auth'=> true], 200);

        } catch (\Exception $e){
            error_log($e->getMessage());
            return response()->json(['error'=> 'could_not_create_user'], 500);
        }
    }

    /**
     * API Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        try {
            JWTAuth::invalidate($request->get('token'));
            return response()->json(['auth'=> true], 200);
        } catch (JWTException $e) {
            error_log($e->getMessage());
            return response()->json(['error' => 'failed_to_logout,_please_try_again'], 500);
        }
    }
}
