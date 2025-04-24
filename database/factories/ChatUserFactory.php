<?php

namespace Database\Factories;

use Dcodegroup\LaravelChat\Models\Chat;
use Dcodegroup\LaravelChat\Models\ChatUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatUser>
 */
class ChatUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'chat_id' => Chat::factory(),
            'user_id' => config('laravel-chat.user_model')::factory(),
        ];
    }
}
