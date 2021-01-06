<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;
use App\User;
class AuthController extends Controller
{
/**
 * @var \Tymon\JWTAuth\JWTAuth
 */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:255',
            'password' => 'required',
        ]);

        try {

            // $user = User::where('email', '=', 'sam@mail.com')->first();

            // if (!$token = $this->jwt->fromUser($user)) {
            //     return response()->json(['user_not_found'], 404);
            // }
             if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                 return response()->json(['user_not_found'], 404);
             }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
        }

        return response()->json(compact('token'));
    }
	
	
	public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'User successfully registered'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 400);
        }

    }

      public function accessauth()
    {
		echo "user can access this function";
	}

	
      public function userauth()
    {

        $token = $this->jwt->getToken();
        $this->jwt->user();
        $data = $this->jwt->setToken($token)->toUser();
        print_r($data);
        // echo "inside controller";

    }

}