<?php

namespace Dcodegroup\DCodeChat\Data;

use Spatie\DataTransferObject\DataTransferObject;

class ChatUserAttributes extends DataTransferObject
{
    public function __construct(
        public string $userDisplayName,
        public string $userChatTitle,
        public string $userChatSubtitle,
        public ?string $chatAvatarUrl = null,
        public ?string $userAvatarUrl = null,
    ) {}
}
