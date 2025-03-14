<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'lname' => 'required|string|max:255',
            'fname' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
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

        try {
            $users = User::create([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'password' => $request->password,
                'address' => $request->address,
            ]);
            return response()->json([
                'data' => $users,
                'message' => "Register Successfull",
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
                'message' => 'Error while creating user'
            ], 500);
        }

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "data" => null,
                    "msg" => 'Check your mail or password'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'data' => null,
                'message' => 'Access token fails'
            ], 401);
        }

        $user = JWTAuth::user();

        return response()->json([
            "data" => $user,
            "token" => $token
        ]);
    }


    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            if ($token) {
                JWTAuth::invalidate($token);
            }

            return response()->json([
                "message" => "Logout successful"
            ]);
        } catch (JWTException $e) {
            return response()->json([
                "message" => "Failed to invalidate token"
            ], 500);
        }
    }


    public function userProfile()
    {
        return response()->json(Auth::user());
    }
}

