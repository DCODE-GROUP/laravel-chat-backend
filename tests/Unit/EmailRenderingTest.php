<?php

use App\Models\User;
use Dcodegroup\DCodeChat\Mail\UnreadMessageSummary;
use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Models\ChatMessage;
use Dcodegroup\DCodeChat\Models\ChatUser;

it('renders the unread message summary email', function () {
    $user = User::factory()->create(['name' => 'Test User']);

    $chat = Chat::factory()->create(['id' => 'chat_1']);
    $chat2 = Chat::factory()->create(['id' => 'chat_2']);

    ChatUser::factory()->create([
        'user_id' => $user->id,
        'chat_id' => $chat->id,
        'last_read_at' => now()->subHours(3),
        'has_new_messages' => true,
    ]);
    $sender = User::factory()->create();

    $messages = collect([
        ChatMessage::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $sender->id,
            'created_at' => now(),
        ]),
        ChatMessage::factory()->create([
            'chat_id' => $chat2->id,
            'user_id' => $sender->id,
            'created_at' => now(),
        ]),
    ]);

    $mailable = new UnreadMessageSummary($messages, $user);
    $html = $mailable->render();

    expect($html)->toContain('You have unread messages');
    expect($html)->toContain('Test User');
});
