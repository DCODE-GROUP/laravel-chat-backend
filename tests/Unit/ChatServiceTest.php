<?php

use App\Models\User;
use Dcodegroup\DCodeChat\Data\ChatUserAttributes;
use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Support\ChatResolver;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\artisan;

beforeEach(function () {
    Mail::fake();
});

it('can initiate a direct chat between users', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();

    ChatResolver::setUserChatAttributeResolver(function (Chat $chat, User $user) {
        return new ChatUserAttributes(
            'Test User',
            'Chat with Test User',
            'This is a test chat',
            'https://example.com/chat-avatar.png',
            'https://example.com/avatar.png',
            null,
            'Hello!',
            'info',
        );
    });


    $chatService = new \Dcodegroup\DCodeChat\Services\ChatService();
    $chat = $chatService->startChat($fromUser, [$toUser]);

    expect($chat)->toBeInstanceOf(Chat::class);
    expect($chat->users->pluck('id'))->toContain($fromUser->id);
    expect($chat->users->pluck('id'))->toContain($toUser->id);
});

it('does not initiate duplicate chats between the same users', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();

    $chatService = new \Dcodegroup\DCodeChat\Services\ChatService();
    $chat1 = $chatService->startChat($fromUser, [$toUser]);
    $chat2 = $chatService->startChat($fromUser, [$toUser]);

    expect($chat1->id)->toEqual($chat2->id);
});
