<?php

namespace Dcodegroup\DCodeChat\Factories;

use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Models\ChatUser;
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
            'user_id' => config('dcode-chat.user_model')::factory(),
        ];
    }

    public function modelName(): string
    {
        return ChatUser::class;
    }
}
