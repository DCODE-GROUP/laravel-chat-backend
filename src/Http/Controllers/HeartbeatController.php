<?php

namespace Dcodegroup\DCodeChat\Http\Controllers;

use Dcodegroup\DCodeChat\Facades\ChatService;

class HeartbeatController extends ChatController
{
    /**
     * Handle the heartbeat request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        $chats = ChatService::getChatsForUser(auth()->user(), request()->input('query', ''));
        $newMessages = [];
        if ($currentChat = request()->input('currentChat')) {
            // If a current chat is specified, check if it has new messages
            /* @var \Dcodegroup\DCodeChat\Models\Chat $currentChat */
            $currentChat = $chats->firstWhere('id', $currentChat);
            if ($currentChat) {
                $currentChat->load('messages');

                if ($afterId = request()->input('lastMessageId')) {
                    // If an 'after' parameter is provided, filter chats to only include those after the specified ID
                    $currentChat->load('messages');
                    $newMessages = $currentChat->messages->filter(function ($chat) use ($afterId) { // @phpstan-ignore-line
                        return $chat->id > $afterId;
                    })->values();
                } else {
                    // If no 'after' parameter is provided, return all messages
                    $newMessages = $currentChat->messages; // @phpstan-ignore-line
                }
            }
        }

        // Return all the data needed to render the chat interface
        return response()->json([
            'status' => 'ok',
            'chats' => $chats,
            'newMessages' => $newMessages,
        ]);
    }
}
