<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            if ($token = $this->guard()->attempt($credentials)) {
                $token = $this->respondWithToken($token);
                return response()->json(compact('token'), 200);
            }

            return response()->json(['error' => 'invalid email or password'], 401);

        } catch (\Exception $e) {
            return response()->json(['error' => 'could not create token'], 500);
        }
    }

    /**
     * API Refresh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try{
            $token = $this->respondWithToken($this->guard()->refresh());
            return response()->json(compact('token'), 200);
        } catch(\Exception $e){
            return response()->json(['error' => 'error generating token'], 500);
        }
    }

    /**
     * API User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request){
        $user = $this->guard()->user();
        return response()->json(compact('user'), 200);
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
            'name' => 'required',
            'username' => 'required|unique:users',
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

            $credentials = $request->only('email', 'password');
            if ($token = $this->guard()->attempt($credentials)) {
                $token = $this->respondWithToken($token);
                return response()->json(compact('token'), 200);
            }

            return response()->json(['error' => 'error'], 401);

        } catch (\Exception $e){
            error_log($e->getMessage());
            return response()->json(['error'=> 'could not create user'], 500);
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
        try {
            $this->guard()->logout();
            return response()->json(['auth'=> true], 200);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json(['error' => 'failed to logout, please try again'], 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
