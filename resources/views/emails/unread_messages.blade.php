# You have unread messages 

Hi {{ $user->name }},

You have **{{ $messages->count() }} unread message{{ $messages->count() > 1 ? 's' : '' }}** in the following chats:

<ul>
@foreach($messages->groupBy('chat_id') as $chatId => $msgs)
    <li>{{ optional($msgs->first()->chat->users->firstWhere('user_id', $user->id))->chat_title ?? 'Chat ID: ' . $chatId }}</li>
@endforeach
</ul>

<a href="{{ route('dcode-chat.chat.index') }}" style="color: #3490dc; text-decoration: none;">
    View Messages
</a>

Thanks!<br>
