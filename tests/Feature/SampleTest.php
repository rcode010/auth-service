<?php

test('it should open home page.', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
