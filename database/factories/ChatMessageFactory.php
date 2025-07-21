<?php

namespace Dcodegroup\DCodeChat\Factories;

use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Models\ChatMessage;
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
            'user_id' => config('dcode-chat.user_model')::factory(),
        ];
    }

    public function modelName()
    {
        return ChatMessage::class;
    }
}
