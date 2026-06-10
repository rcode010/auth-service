<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
        $exceptions->render(function (AuthenticationException $e){
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ],401);
        });
        $exceptions->render(function (ValidationException $e){
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ],422);
        });
        $exceptions->render(function (ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
            ],404);
        });
        $exceptions->render(function (ThrottleRequestsException $e){
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts, Please try again later.',
            ],429);
        });
        $exceptions->render(function (TokenBlacklistedException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token has been blacklisted.',
            ], 401);
        });
    })->create();
