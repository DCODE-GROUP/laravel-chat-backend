<?php

namespace Dcodegroup\DCodeChat\Events;

use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DCodeChatCreatedForUser implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Chat $chat, protected Authorizable $user)
    {
        $this->chat->unsetRelation('messages');
    }

    public function broadcastOn(): array
    {
        $broadcasts = [];
        $broadcasts[] = new PrivateChannel('dcode-chat.'.$this->user->id);

        return $broadcasts;
    }

    public function broadcastAs(): string
    {
        return 'DCodeChatCreatedForUser';
    }
}
