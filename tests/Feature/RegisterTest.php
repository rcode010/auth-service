<?php

use App\Models\User;
describe("Registration Tests", function () {

    test('User can register successfuly', function () {
        $email= fake()->email();
        $name= fake()->name();
        $response= $this->postJson("/api/auth/register",[
            'name'=>$name,
            'email'=>$email,
            'password'=>'Password123',
        ]);

        expect($response)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'name',
                'email',
                'updated_at',
                'created_at',
                'id'
            ]
        ])->assertJson([
            'success' => true,
            'message' => "User created successfully",
            'data'=>[
                'name' => $name,
                'email'=>$email
            ]
        ]);
        $this->assertDatabaseHas('users', ['email' => $email]);
    });

    test('Return validation error if user is already registered', function () {
        $payload= [
            'name'=>fake()->name(),
            'email'=>fake()->email(),
            'password'=>'Password123',
        ];
        $this->postJson("/api/auth/register",$payload);

        $this->postJson("/api/auth/register",$payload)->assertStatus(422);

    });
    test('Return validation error if password is less than 8 characters', function () {
        $payload= [
            'name'=>fake()->name(),
            'email'=>fake()->email(),
            'password'=>'Pass',
        ];

        $this->postJson("/api/auth/register",$payload)->assertStatus(422);
    });
    test('Return validation error if required fields are missing', function () {
        $payload= [
            'email'=>fake()->email(),
            'password'=>'Password123',
        ];
        $this->postJson("/api/auth/register",$payload)->assertStatus(422);
    });

});
