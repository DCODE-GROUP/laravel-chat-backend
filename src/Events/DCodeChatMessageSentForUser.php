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

    public function __construct(public Chat $chat, public ChatMessage $message, protected Authorizable $user)
    {
        $this->chat->unsetRelation('messages');
        $this->chat->unsetRelation('users');
    }

    public function broadcastOn(): array
    {
        $broadcasts = [
            new PrivateChannel('dcode-chat.'.$this->user->id), // @phpstan-ignore-line
        ];

        return $broadcasts;
    }

    public function broadcastAs(): string
    {
        return 'DCodeChatMessageSentForUser';
    }

    public function broadcastWith(): array
    {
        return [
            'chat' => [
                'id' => $this->chat->id,
                'pivot' => [
                    'has_new_messages' => $this->chat->users()->firstWhere('users.id', $this->user->id)?->pivot->has_new_messages,
                ],
            ],
            'message' => $this->message,
        ];
    }
}
