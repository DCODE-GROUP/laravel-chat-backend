<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

use Dcodegroup\DCodeChat\Events\DCodeChatMessageCreated;
use Dcodegroup\DCodeChat\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all chats for the authenticated user
        $chats = Chat::where('user_id', auth()->id())->get();

        return response()->json($chats);
    }

    public function show(Request $request, Chat $chat)
    {
        // Fetch a specific chat by ID
        return response()->json($chat);
    }

    public function store(Request $request)
    {
        $chat = Chat::query()->byRelation($request->input('chattable_type'), $request->input('chattable_id'))->first();

        if ($chat instanceof Chat) {
            return redirect()->route(config('dcode-chat.route_name').'.chat.show', $chat);
        }

        $chat = Chat::query()->create([
            'chattable_type' => $request->input('chattable_type'),
            'chattable_id' => $request->input('chattable_id'),
            'open' => true,
        ]);

        event(new DCodeChatMessageCreated($chat));

        return redirect()->route(config('dcode-chat.route_name').'.chat.show', $chat);
    }
}
