<?php

namespace Dcodegroup\DCodeChat\Events;

use Dcodegroup\DCodeChat\Models\ChatUser;

class DCodeChatUserAssigned
{
    public function __construct(public ChatUser $chatUser) {}
}
