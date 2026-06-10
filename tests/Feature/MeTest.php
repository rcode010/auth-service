<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
   $this->email = fake()->email;
   $this->name = fake()->name;
   $this->user = User::factory()->create([
       'email' => $this->email,
       'name' => $this->name,
       'password' => Hash::make('Password123')
   ]);
});

test('Authenticated user gets their profile data', function () {
    $this->actingAs($this->user,'api')->getJson("/api/auth/me")->assertJsonStructure([
        'success',
        'data' => [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ]
    ])->assertJson([
        'success' => true,
        'data' => [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]
    ])->assertStatus(200);
});
test("Unauthenticated user can not get their profile data", function () {
    $this->getJson("/api/auth/me")->assertStatus(401);
});
test("Invalid token must return 401", function () {
    $this->withHeaders([
        'Authorization' => 'Bearer invalidtoken123'
    ])->getJson('/api/auth/me')->assertStatus(401);

});
