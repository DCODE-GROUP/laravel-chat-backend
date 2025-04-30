<?php

namespace Dcodegroup\LaravelChat\Http\Controllers;

use Dcodegroup\LaravelChat\Events\LaravelChatMessageCreated;
use Dcodegroup\LaravelChat\Http\Requests\Chat\CreateRequest;
use Dcodegroup\LaravelChat\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChatController extends Controller
{
    public function create(CreateRequest $request): RedirectResponse
    {
        $chat = Chat::query()->byRelation($request->input('chattable_type'), $request->input('chattable_id'))->first();

        if ($chat instanceof Chat) {
            return redirect()->route(config('laravel-chat.route_name').'.chats.show', $chat);
        }

        $chat = Chat::query()->create([
            'chattable_type' => $request->input('chattable_type'),
            'chattable_id' => $request->input('chattable_id'),
            'open' => true,
        ]);

        event(new LaravelChatMessageCreated($chat));

        return redirect()->route(config('laravel-chat.route_name').'.chats.show', $chat);
    }

    public function show(Request $request, Chat $chat) {}
}
