<?php

namespace Dcodegroup\DCodeChat\Factories;

use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chat>
 */
class ChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'chatable_id' => fake()->randomDigit(),
            'chatable_type' => fake()->randomElement(['Transport::class', 'Client::class']),
            'open' => true,
        ];
    }

    public function modelName(): string
    {
        return Chat::class;
    }
}
