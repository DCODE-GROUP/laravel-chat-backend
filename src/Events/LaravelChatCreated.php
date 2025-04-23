<?php

namespace Dcodegroup\LaravelChat\Events;

use Dcodegroup\LaravelChat\Models\Chat;

class LaravelChatCreated
{
    public function __construct(public Chat $chat) {}
}
