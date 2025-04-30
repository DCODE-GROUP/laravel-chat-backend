<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('if relation not found for chat it makes a new one', function () {

    $this->assertDatabaseEmpty('chats');

    actingAs($this->user)->get(route(config('laravel-chat.route_name').'.create'))->assertRedirectToRoute(config('laravel-chat.route_name').'.chats.show', 1);

});
