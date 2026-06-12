<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshToken;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController
{
    // Register
    public function register(RegisterRequest $request)
    {

        $user = User::create($request->validated());

        return response([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    // Login
    public function login(LoginRequest $request)
    {
        $request->validated();
        $token = JWTAuth::attempt($request->only('email', 'password'));
        if ($token) {
            $user = auth()->user();
            $refreshToken = bin2hex(random_bytes(32));
            Redis::set('refresh_token:'.$refreshToken, $user->id, 'EX', 60 * 60 * 24 * 7);

            return response([
                'success' => true,
                'message' => 'User logged in successfully',
                'user' => auth()->user(),
                'accessToken' => $token,
                'refreshToken' => $refreshToken,
            ], 200);
        }

        return response([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }

    // profile
    public function profile(Request $request)
    {
        return response([
            'success' => true,
            'data' => auth()->user(),
        ], 200);
    }

    // Logout
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response([
            'success' => true,
            'message' => 'User logged out successfully',
        ], 200);
    }

    // Refresh Token
    public function refreshToken(RefreshToken $request)
    {
        $refreshToken = $request->refresh_token;
        $userId = Redis::get('refresh_token:'.$refreshToken);
        if ($userId) {
            $user = User::find($userId);
            $accessToken = JWTAuth::fromUser($user);
            $refreshToken = bin2hex(random_bytes(32));
            Redis::del('refresh_token:'.$request->refresh_token);
            Redis::set('refresh_token:'.$refreshToken, $userId, 'EX', 60 * 60 * 24 * 7);

            return response([
                'success' => true,
                'message' => 'Refresh token successfully',
                'accessToken' => $accessToken,
                'refreshToken' => $refreshToken,
            ]);
        }

        return response([
            'success' => false,
            'message' => 'Refresh token is invalid',
        ], 401);
    }
}
