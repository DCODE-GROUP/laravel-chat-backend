<?php

use App\Models\User;
use Dcodegroup\DCodeChat\Mail\UnreadMessageSummary;
use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Models\ChatEmailNotification;
use Dcodegroup\DCodeChat\Models\ChatMessage;
use Dcodegroup\DCodeChat\Models\ChatUser;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\artisan;

beforeEach(function () {
    Mail::fake();
});

it('sends a summary email if user has unread messages and has not logged in for 1 hour', function () {
    $user = User::factory()->create([
        'last_login_at' => now()->subHours(2),
    ]);

    $chat = Chat::factory()->create();
    ChatUser::factory()->create([
        'user_id' => $user->id,
        'chat_id' => $chat->id,
        'last_read_at' => now()->subHours(3),
        'has_new_messages' => true,
    ]);

    $sender = User::factory()->create();

    ChatMessage::factory()->create([
        'chat_id' => $chat->id,
        'user_id' => $sender->id,
        'created_at' => now()->subMinutes(90),
    ]);

    artisan('chat:send-unread-notifications')
        ->assertSuccessful();

    Mail::assertQueued(UnreadMessageSummary::class, 1);

    $this->assertDatabaseHas('chat_email_notifications', [
        'user_id' => $user->id,
    ]);
});

it('does not send email if user logged in within the past hour', function () {
    $user = User::factory()->create([
        'last_login_at' => now()->subMinutes(30),
    ]);

    $chat = Chat::factory()->create();
    ChatUser::factory()->create([
        'user_id' => $user->id,
        'chat_id' => $chat->id,
        'last_read_at' => now()->subHours(3),
        'has_new_messages' => true,
    ]);

    $sender = User::factory()->create();

    ChatMessage::factory()->create([
        'chat_id' => $chat->id,
        'user_id' => $sender->id,
        'created_at' => now()->subHour(),
    ]);

    artisan('chat:send-unread-notifications')
        ->assertSuccessful();

    Mail::assertNothingSent();
});

it('does not send duplicate emails for the same message batch', function () {
    $user = User::factory()->create([
        'last_login_at' => now()->subHours(2),
    ]);

    $chat = Chat::factory()->create();
    ChatUser::factory()->create([
        'user_id' => $user->id,
        'chat_id' => $chat->id,
        'last_read_at' => now()->subHours(3),
        'has_new_messages' => true,
    ]);

    $sender = User::factory()->create();

    $message = ChatMessage::factory()->create([
        'chat_id' => $chat->id,
        'user_id' => $sender->id,
        'created_at' => now()->subMinutes(70),
    ]);

    ChatEmailNotification::create([
        'user_id' => $user->id,
        'last_message_at' => $message->created_at,
        'last_notified_at' => now(),
    ]);

    artisan('chat:send-unread-notifications')
        ->assertSuccessful();

    Mail::assertNothingSent();
});
