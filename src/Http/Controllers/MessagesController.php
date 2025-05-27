<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

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

        // Fetch messages for the chat
        $messages = $chat->messages()->with('user')->get();

        if ($request->input('markAsRead', 'false') === 'true') {
            // Update pivot data for the current user to indicate they have read all messages
            $chat->users()->updateExistingPivot(auth()->id(), [
                'has_new_messages' => false,
                'last_read_at' => now(),
            ]);
        }

        return response()->json([
            'chat' => auth()->user()->chats()->with('messages')->where('chat_id', $chat->id)->first()->toArray(),
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
