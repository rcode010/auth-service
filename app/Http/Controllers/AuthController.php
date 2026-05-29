<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
}
