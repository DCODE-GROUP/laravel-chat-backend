<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $chats = Chat::where('user_id', auth()->id())->get();

        return response()->json($chats);
    }

    public function show(Request $request, Chat $chat)
    {
        // Ensure the authenticated user has access to the chat
        if (! $chat->users()->where('user_id', auth()->id())->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($chat);
    }
}
