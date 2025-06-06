<?php

namespace Dcodegroup\DCodeChat\Support\Traits;

use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Contracts\Auth\Access\Authorizable;

trait ChatableModel
{
    /**
     * Get the chat associated with the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function chat()
    {
        return $this->morphOne(Chat::class, 'chatable');
    }

    /**
     * Get the chat ID for the model.
     *
     * @return int|null
     */
    public function getChatIdAttribute()
    {
        return $this->chat ? $this->chat->id : null;
    }

    public function getChatDisplayName(Chat $chat, Authorizable $user)
    {
        throw new \Exception('Method getChatDisplayName not implemented in '.static::class);
    }

    public function getChatTitle(Chat $chat, Authorizable $user)
    {
        throw new \Exception('Method getChatTitle not implemented in '.static::class);
    }

    public function getChatDescription(Chat $chat, Authorizable $user)
    {
        throw new \Exception('Method getChatDescription not implemented in '.static::class);
    }

    public function getChatAvatar(Chat $chat, Authorizable $user)
    {
        throw new \Exception('Method getChatAvatar not implemented in '.static::class);
    }

    public function getUserAvatar(Chat $chat, Authorizable $user)
    {
        throw new \Exception('Method getUserAvatar not implemented in '.static::class);
    }
}
