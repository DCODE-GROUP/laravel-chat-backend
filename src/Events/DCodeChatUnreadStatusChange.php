<?php

namespace Dcodegroup\DCodeChat\Events;

use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DCodeChatUnreadStatusChange implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Collection $unreadChats;

    public function __construct(public Chat $chat, protected Authorizable $user)
    {
        $this->unreadChats = $user->chats()->where('has_new_messages', true)->get(); // @phpstan-ignore-line
        $this->chat->unsetRelation('messages');
    }

    public function broadcastOn(): array
    {
        $broadcasts = [];
        $broadcasts[] = new PrivateChannel('dcode-chat.'.$this->user->id); // @phpstan-ignore-line

        return $broadcasts;
    }

    public function broadcastAs(): string
    {
        return 'DCodeChatUnreadStatusChange';
    }
}
