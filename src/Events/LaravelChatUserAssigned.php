<?php

namespace Dcodegroup\LaravelChat\Events;

use Dcodegroup\LaravelChat\Models\ChatUser;

class LaravelChatUserAssigned
{
    public function __construct(public ChatUser $chatUser) {}
}
