<?php

namespace Dcodegroup\DCodeChat\Support;

use Dcodegroup\DCodeChat\Data\ChatUserAttributes;
use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ChatResolver
{
    protected static $usersResolver;

    protected static $chatAttributeResolver;

    /**
     * Resolve the user, optionally with a model context.
     */
    public static function resolveUsers(?Model $model = null): array|Collection
    {
        if (! static::$usersResolver) {
            throw new \Exception('User resolver not set.');
        }

        return call_user_func(static::$usersResolver, $model);
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
     * @param  callable  $resolver  A callback that takes a Model|null and returns an array or Collection
     * @return void
     */
    public static function setUsersResolver(callable $resolver)
    {
        static::$usersResolver = $resolver;
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
