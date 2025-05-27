<?php

namespace Dcodegroup\DCodeChat\Events;

use Dcodegroup\DCodeChat\Models\Chat;

class DCodeChatCreated
{
    public function __construct(public Chat $chat) {}
}
