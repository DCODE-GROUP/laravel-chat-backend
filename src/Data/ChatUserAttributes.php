<?php

namespace Dcodegroup\DCodeChat\Data;

use Spatie\LaravelData\Data;

class ChatUserAttributes extends Data
{
    public function __construct(
        public string $userDisplayName,
        public string $userChatTitle,
        public string $userChatSubtitle,
        public ?string $chatAvatarUrl = null,
        public ?string $userAvatarUrl = null,
        public ?string $userChatDescriptionLink = null,
        public ?string $userChatBubbleMessage = null,
        public ?string $userChatBubbleClass = null,
    ) {}
}
