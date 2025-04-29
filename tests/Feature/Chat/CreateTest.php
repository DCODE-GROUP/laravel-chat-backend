<?php

test('if relation not found for chat it makes a new one', function () {

    $this->assertDatabaseMissing('chats', [
        'chatable_id' => 1,
        'chatable_type' => 'App\Models\Transport',
    ]);

    $user = \Dcodegroup\LaravelChat\Models\User::factory()->create();
    $user2 = \Dcodegroup\LaravelChat\Models\User::factory()->create();

    $chat = $user->chat($user2);

    expect($chat)->toBeInstanceOf(\Dcodegroup\LaravelChat\Models\Chat::class);
})->skip(fn() => !class_exists('Dcodegroup\LaravelChat\Models\User'));
});
