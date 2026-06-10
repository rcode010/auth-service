<?php

use App\Models\User;

beforeEach(function () {
    $this->email = fake()->email();
    RateLimiter::clear($this->email . '|127.0.0.1');
    User::factory()->create(["email"=>$this->email,"password"=>Hash::make('Password123')]);

});
describe("Login Tests", function () {

    test('User login successfully and return access token and refresh token ', function () {

        $this->postJson("/api/auth/login",[
            "email"=>$this->email,
            "password"=>"Password123"
        ])->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            'accessToken',
            'refreshToken',
        ])->assertJson([
            'success' => true,
            'message' => 'User logged in successfully',
            'user' => [
                'email'=>$this->email,
            ]
        ]);
    });

    test('Wrong password returns 401', function () {
        $this->postJson("/api/auth/login",[
            "email"=>$this->email,
            "password"=>"WrongPassword123"
        ])->assertStatus(401);
    });

    test('Wrong email returns 401', function () {
        $this->postJson("/api/auth/login",[
            "email"=>fake()->email(),
            "password"=>"Password123"
        ])->assertStatus(401);
    });
    test('Missing fields returns 422', function () {
        $this->postJson("/api/auth/login",[
            "email"=>fake()->email(),
        ])->assertStatus(422);
        $this->postJson("/api/auth/login",[
            "password"=>"Password123"
        ])->assertStatus(422);
    });



});
