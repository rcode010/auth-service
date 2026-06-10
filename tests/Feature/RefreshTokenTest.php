<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = JWTAuth::fromUser($this->user);
});

test('Valid refresh token returns new access token and new refresh token', function () {
    $this->postJson("/)
});
