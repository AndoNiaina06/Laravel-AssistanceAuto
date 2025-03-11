<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'password.min' => 'Password must be superior to 6 characters',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
            ], 422);
        }

        try{
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            return response()->json([
                'data' => $request,
                'message'=> "Register Successfull",
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'data'=>$e->getMessage(),
                'msg' => 'Failed to create user '
            ], 500);
        }

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken=JWTAuth::attempt($credentials)) return response()->json([
                "data"=>null,
                "msg"=>'check your mail or password'
            ],401);
        }
        catch (JWTException $e) {
            return response()->json([
                'data'=>null,
                'msg'=>'Acces token fails']
                ,401);
        }

        $user=JWTAuth::user();
        $refreshToken = JWTAuth::claims(['exp' => now()->addDays(7)->timestamp])->fromUser($user);

        $cookie = Cookie::make('refresh_token', $refreshToken, 10080, '/', null, true, true, false, 'Strict');

        return response()->json([
            "data"=>$user,
            "accessToken"=>$accessToken
        ])->withCookie($cookie);
    }

    public function logout()
    {
        try {

            $accessToken = JWTAuth::getToken();
            if ($accessToken) {
                JWTAuth::invalidate($accessToken);
            }

            $cookie = Cookie::forget('refresh_token');

            return response()->json([
                "msg" => "logout successfull"
            ])->withCookie($cookie);
        } catch (JWTException $e) {
            return response()->json([
                "msg" => "Impossible d'invalider le token"
            ], 500);
        }
    }

    public function userProfile()
    {
        return response()->json(Auth::user());
    }
}

