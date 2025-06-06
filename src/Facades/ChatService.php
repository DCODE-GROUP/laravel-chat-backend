<?php

namespace Dcodegroup\DCodeChat\Facades;

use Dcodegroup\DCodeChat\Services\ChatService as ChatServiceClass;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Eloquent\Collection getChatsForUser(\Illuminate\Database\Eloquent\Model $user)
 * @method static \Dcodegroup\DCodeChat\Models\Chat getChatById(\Illuminate\Database\Eloquent\Model $user, int $chatId)
 * @method static \Dcodegroup\DCodeChat\Models\Chat startChat(Authorizable $fromUser, ?array $toUsers = [], ?Model $forModel = null)
 */
class ChatService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ChatServiceClass::class;
    }
}
