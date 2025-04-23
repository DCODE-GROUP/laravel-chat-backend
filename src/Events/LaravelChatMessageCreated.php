<?php

namespace Dcodegroup\LaravelChat\Events;

use Dcodegroup\LaravelChat\Models\ChatMessage;

class LaravelChatMessageCreated
{
    public function __construct(public ChatMessage $chatMessage) {}
}
