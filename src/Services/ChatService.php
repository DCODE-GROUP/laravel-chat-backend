<?php

namespace Dcodegroup\DCodeChat\Services;

use Dcodegroup\DCodeChat\Events\DCodeChatMessageCreated;
use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Support\ChatResolver;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

class ChatService
{
    public function getChatsForUser(Model $user)
    {
        // Fetch all chats for the given user
        return $user->chats()->get();
    }

    public function getChatById(Model $user, $chatId)
    {
        // Fetch a specific chat by ID for the given user
        return $user->chats()->find($chatId);
    }

    public function sendMessageToChat(Model $fromUser, Chat $chat, $message)
    {
        $message = $chat->messages()->create([
            'user_id' => $fromUser->id,
            'message' => $message,
        ]);

        // Update pivot data for all other users in the chat to indicate they have new messages
        $userIds = $chat->users()
            ->where('user_id', '!=', $fromUser->id)->pluck('user_id');

        $chat->users()->updateExistingPivot($userIds, [
            'has_new_messages' => true,
        ]);

        event(new DCodeChatMessageCreated($message));

        return $message;
    }

    public function startChat(Authorizable $fromUser, ?Authorizable $toUser = null, ?Model $forModel = null)
    {
        // Logic to start a chat for the user
        if ($forModel) {
            $chat = Chat::firstOrCreate([
                'chatable_type' => $forModel ? get_class($forModel) : null,
                'chatable_id' => $forModel?->id,
            ]);
        } else {
            $chat = Chat::create();
        }

        if (! $toUser) {
            $toUser = ChatResolver::resolveUser($forModel);
        }
        $chat->users()->syncWithoutDetaching([$fromUser->id, $toUser->id]);
        $chat->refresh();

        $userChatAttributes = ChatResolver::resolveUserChatAttributes($chat, $fromUser);
        if ($userChatAttributes->userAvatarUrl === null) {
            $userChatAttributes->userAvatarUrl = route(config('dcode-chat.route_name').'.chat.user_avatar', [$chat, $fromUser]);
        }

        if ($userChatAttributes->chatAvatarUrl === null) {
            $userChatAttributes->chatAvatarUrl = route(config('dcode-chat.route_name').'.chat.default_avatar', $chat);
        }

        $chat->users()->updateExistingPivot($fromUser->id, [
            'user_name' => $userChatAttributes->userDisplayName,
            'user_avatar' => $userChatAttributes->userAvatarUrl,
            'chat_title' => $userChatAttributes->userChatTitle,
            'chat_description' => $userChatAttributes->userChatSubtitle,
            'chat_avatar' => $userChatAttributes->chatAvatarUrl,
        ]);

        $userChatAttributes = ChatResolver::resolveUserChatAttributes($chat, $toUser);
        if ($userChatAttributes->userAvatarUrl === null) {
            $userChatAttributes->userAvatarUrl = route(config('dcode-chat.route_name').'.chat.user_avatar', [$chat, $toUser]);
        }
        if ($userChatAttributes->chatAvatarUrl === null) {
            $userChatAttributes->chatAvatarUrl = route(config('dcode-chat.route_name').'.chat.default_avatar', $chat);
        }

        $chat->users()->updateExistingPivot($toUser->id, [
            'user_name' => $userChatAttributes->userDisplayName,
            'user_avatar' => $userChatAttributes->userAvatarUrl,
            'chat_title' => $userChatAttributes->userChatTitle,
            'chat_description' => $userChatAttributes->userChatSubtitle,
            'chat_avatar' => $userChatAttributes->chatAvatarUrl,
        ]);

        return $chat;
    }
}
