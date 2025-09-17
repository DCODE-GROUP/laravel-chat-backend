<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

use Dcodegroup\DCodeChat\Events\DCodeChatUnreadStatusChange;
use Dcodegroup\DCodeChat\Events\DCodeMarkRead;
use Dcodegroup\DCodeChat\Facades\ChatService;
use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Http\Request;

class MessagesController
{
    public function index(Request $request, Chat $chat)
    {
        // Check that this user is part of the chat
        if (! $chat->users->contains(auth()->user())) {
            abort(403, 'You are not authorized to view messages in this chat.');
        }

        if ($request->input('markAsRead', 'false') === 'true') {
            // Update pivot data for the current user to indicate they have read all messages
            $chat->users()->updateExistingPivot(auth()->id(), [
                'has_new_messages' => false,
                'last_read_at' => now(),
            ]);

            DCodeMarkRead::dispatch(auth()->user(), $chat);

            DCodeChatUnreadStatusChange::dispatch(
                auth()->user()->chats()->where('chat_id', $chat->id)->first(), // @phpstan-ignore-line
                auth()->user()
            );
        }

        return response()->json([
            'chat' => auth()->user()->chats()->with('messages')->where('chat_id', $chat->id)->first()->toArray(), // @phpstan-ignore-line
        ]);
    }

    public function store(Chat $chat, Request $request)
    {
        // Check that this user is part of the chat
        if (! $chat->users->contains(auth()->user())) {
            abort(403, 'You are not authorized to send messages in this chat.');
        }

        $chatMessage = ChatService::sendMessageToChat(auth()->user(), $chat, $request->input('message'));

        return response()->json([
            'message' => $chatMessage,
        ], 201);
    }
}
