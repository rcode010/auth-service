<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController
{
    // Register
    public function register(RegisterRequest $request){

        $user = User::create($request->validated());

        return response([
            "success" => true,
            "message" => "User created successfully",
            "data"=>$user
        ],201);
    }

    # Login
    public function login(LoginRequest $request){
            $request->validated();

        $token = JWTAuth::attempt($request->only('email','password'));
        if($token){
            return response([
                "success" => true,
                "message" => "User logged in successfully",
                "user"=>auth()->user(),
                "token"=>$token
            ],200);
        }
        return response([
            "success" => false,
            "message" => "Invalid email or password"
        ],401);
    }
}
