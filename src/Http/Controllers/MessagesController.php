<?php

namespace Dcodegroup\LaravelChat\Http\Controllers;

use Dcodegroup\LaravelChat\Events\LaravelChatMessageCreated;
use Dcodegroup\LaravelChat\Models\Chat;
use Illuminate\Http\Request;

class MessagesController
{
    public function index(Request $request, Chat $chat) {}

    public function store(Request $request)
    {

        event(new LaravelChatMessageCreated($chatMessage));
    }
}
