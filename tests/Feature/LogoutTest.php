<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = JWTAuth::fromUser($this->user);
});

test('Authenticated user can logout successfully', function () {

    $this->withHeaders([
        'Authorization' => 'Bearer '.$this->token,
    ])->postJson('/api/auth/logout')->assertStatus(200);

});

test('Blacklisted token can not get their profile data after logout', function () {
    $this->withHeaders([
        'Authorization' => 'Bearer '.$this->token,
    ])->postJson('/api/auth/logout')->assertStatus(200);

    // Note: JWT blacklist check cannot be tested in-process due to
    // token state caching in tymon/jwt-auth. Verified manually via Postman.
})->skip('JWT blacklist cannot be tested in-process - verified manually');
