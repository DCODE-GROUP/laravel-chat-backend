<?php

use App\Models\Transport;
use App\Models\User;
use Dcodegroup\DCodeChat\Models\Chat;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can access home page', function () {
    actingAs($this->user)
        ->get('/test')
        ->assertOk()
        ->assertSee('test');
});

test('if relation not found for chat it makes a new one', function () {
    $this->assertDatabaseEmpty('chats');
    $transport = Transport::factory()->create();

    $response = actingAs($this->user)
        ->post(route(config('dcode-chat.route_name').'.chat.create'), [
            'chatable_type' => Transport::class,
            'chatable_id' => (string) $transport->id,
        ]);
    //    dd($response->getContent());

    $item = Chat::query()->latest()->first();
    $response->assertRedirectToRoute(config('dcode-chat.route_name').'.chat.show', $item->id);

});
