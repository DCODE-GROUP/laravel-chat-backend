<?php

namespace Dcodegroup\DCodeChat\Events;

use Dcodegroup\DCodeChat\Models\ChatMessage;

class DCodeChatMessageCreated
{
    public function __construct(public ChatMessage $chatMessage) {}
}
