<?php

namespace Dcodegroup\DCodeChat\Support;

use Dcodegroup\DCodeChat\Data\ChatUserAttributes;
use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

class ChatResolver
{
    protected static $userResolver;

    protected static $chatAttributeResolver;

    /**
     * Resolve the user, optionally with a model context.
     *
     * @return mixed
     */
    public static function resolveUser(?Model $model = null): Authorizable
    {
        if (! static::$userResolver) {
            throw new \Exception('User resolver not set.');
        }

        return call_user_func(static::$userResolver, $model);
    }

    public static function resolveUserChatAttributes(Chat $chat, Authorizable $user): ChatUserAttributes
    {
        if (! static::$chatAttributeResolver) {
            throw new \Exception('Chat attribute resolver not set.');
        }

        return call_user_func(static::$chatAttributeResolver, $chat, $user);
    }

    /**
     * Set the user resolver.
     *
     * @param  callable(?Model $model): Authorizable  $resolver
     * @return void
     */
    public static function setUserResolver(callable $resolver)
    {
        static::$userResolver = $resolver;
    }

    /**
     * Set the chat attribute resolver.
     *
     * @param  callable(Chat $model, Authorizable $user): ChatUserAttributes  $resolver
     */
    public static function setUserChatAttributeResolver(callable $resolver)
    {
        static::$chatAttributeResolver = $resolver;
    }
}
