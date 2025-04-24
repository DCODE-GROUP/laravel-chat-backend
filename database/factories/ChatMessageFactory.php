<?php

namespace Database\Factories;

use Dcodegroup\LaravelChat\Models\Chat;
use Dcodegroup\LaravelChat\Models\ChatMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'chat_id' => Chat::factory(),
            'message' => fake()->sentence(),
            'user_id' => config('laravel-chat.user_model')::factory(),
        ];
    }
}
