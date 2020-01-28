<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;


class AuthenticateController extends Controller
{

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            $user = User::where('email', $credentials['email'])->where(['user_verified' => true])->first();

            if ($user) {
                // attempt to verify the credentials and create a token for the user
                if (!$token = \Tymon\JWTAuth\Facades\JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } else {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }


        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => $e->getMessage()], 500);
        }


        // all good so return the token
        return response()->json(compact('token', 'user'));
    }


    public function refresh()
    {
        try {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::getToken();
            $new_token = \Tymon\JWTAuth\Facades\JWTAuth::refresh($token);

        } catch (\Exception $exception) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json(['new_token' => $new_token]);
    }

}
