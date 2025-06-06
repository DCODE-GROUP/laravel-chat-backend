<?php

namespace Dcodegroup\DCodeChat\Events;

use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DCodeChatMessageSentForUser implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Chat $chat, public ChatMessage $message, protected Authorizable $user) {}

    public function broadcastOn(): array
    {
        $broadcasts = [
            new PrivateChannel('dcode-chat.'.$this->user->id),
        ];

        return $broadcasts;
    }

    public function broadcastAs(): string
    {
        return 'DCodeChatMessageSentForUser';
    }
}
