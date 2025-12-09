<?php

namespace Dcodegroup\DCodeChat\Services;

use Dcodegroup\DCodeChat\Events\DCodeChatCreatedForUser;
use Dcodegroup\DCodeChat\Events\DCodeChatMessageSentForUser;
use Dcodegroup\DCodeChat\Events\DCodeChatUnreadStatusChange;
use Dcodegroup\DCodeChat\Models\Chat;
use Dcodegroup\DCodeChat\Models\ChatUser;
use Dcodegroup\DCodeChat\Support\ChatResolver;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;

class ChatService
{
    public function getChatsForUser(Model $user, ?string $search = null)
    {
        // Fetch all chats for the given user
        $query = $user->chats(); // @phpstan-ignore-line
        if ($search) {
            // If a search term is provided, filter chats by title or description
            $query->where(function ($q) use ($search) {
                $q->where('chat_title', 'like', '%'.$search.'%')
                    ->orWhere('chat_description', 'like', '%'.$search.'%')
                    ->orWhereHas('messages', function ($q) use ($search) {
                        $q->where('message', 'like', '%'.$search.'%');
                    });
            });
        }

        return $query->get();
    }

    public function getChatById(Model $user, $chatId)
    {
        // Fetch a specific chat by ID for the given user
        return $user->chats()->find($chatId); // @phpstan-ignore-line
    }

    public function sendMessageToChat(Model $fromUser, Chat $chat, $message)
    {
        $message = $chat->messages()->create([
            'user_id' => $fromUser->id, // @phpstan-ignore-line
            'message' => $message,
        ]);

        // Update pivot data for all other users in the chat to indicate they have new messages
        $chat->users()->where('user_id', '!=', $fromUser->id)->each(function ($user) use ($chat) { // @phpstan-ignore-line
            $chat->users()->updateExistingPivot($user->id, [ // @phpstan-ignore-line
                'has_new_messages' => true,
            ]);
            DCodeChatUnreadStatusChange::dispatch(
                $user->chats()->where('chat_id', $chat->id)->first(),
                $user
            );
        });

        foreach ($chat->users as $user) {
            DCodeChatMessageSentForUser::dispatch(
                $user->chats()->where('chat_id', $chat->id)->first(), // @phpstan-ignore-line
                $message,
                $user
            );
        }

        return $message;
    }

    public function findChat(Authorizable $fromUser, ?array $toUsers = [], ?Model $forModel = null): ?Chat
    {
        $query = ChatUser::query()->join('chats', 'chats.id', '=', 'chat_users.chat_id');

        if ($forModel) {
            $query->where('chats.chatable_type', get_class($forModel)) // @phpstan-ignore-line
                ->where('chats.chatable_id', $forModel->id); // @phpstan-ignore-line
        } else {
            $query->whereNull('chats.chatable_type') // @phpstan-ignore-line
                ->whereNull('chats.chatable_id');
        }

        $chatId = $query->whereIn('chat_users.user_id', [$fromUser->id, ...$toUsers])   // @phpstan-ignore-line
            ->groupBy('chat_users.chat_id')
            ->havingRaw('COUNT(DISTINCT chat_users.user_id) = '.(count($toUsers) + 1))          // and *only* these users
            ->value('chat_users.chat_id');                                 // get the matching chat_id (or null)

        if ($chatId) {
            return Chat::find($chatId);
        }

        return null;
    }

    public function startChat(Authorizable $fromUser, ?array $toUsers = [], ?Model $forModel = null)
    {
        // Logic to start a chat for the user
        if (count($toUsers) == 0) {
            $toUsers = ChatResolver::resolveUsers($forModel);
        } else {
            $toUsers = collect($toUsers);
        }

        if ($forModel) {
            $chat = Chat::firstOrCreate([
                'chatable_type' => get_class($forModel),
                'chatable_id' => $forModel->id, // @phpstan-ignore-line
            ]);
        } else {
            // Check if a chat already exists between these users
            $chat = $this->findChat($fromUser, $toUsers->pluck('id')->toArray()) ?: Chat::create();
        }

        $chat->users()->syncWithoutDetaching([$fromUser->id]); // @phpstan-ignore-line
        $chat->users()->syncWithoutDetaching($toUsers->pluck('id')->toArray());
        $chat->refresh();

        $userChatAttributes = ChatResolver::resolveUserChatAttributes($chat, $fromUser);

        $chat->users()->updateExistingPivot($fromUser->id, [ // @phpstan-ignore-line
            'user_name' => $userChatAttributes->userDisplayName,
            'user_avatar' => $userChatAttributes->userAvatarUrl,
            'chat_title' => $userChatAttributes->userChatTitle,
            'chat_description' => $userChatAttributes->userChatSubtitle,
            'chat_description_link' => $userChatAttributes->userChatDescriptionLink,
            'chat_bubble_message' => $userChatAttributes->userChatBubbleMessage,
            'chat_bubble_class' => $userChatAttributes->userChatBubbleClass,
            'chat_avatar' => $userChatAttributes->chatAvatarUrl,
        ]);

        foreach ($toUsers as $toUser) {
            $userChatAttributes = ChatResolver::resolveUserChatAttributes($chat, $toUser);

            $chat->users()->updateExistingPivot($toUser->id, [
                'user_name' => $userChatAttributes->userDisplayName,
                'user_avatar' => $userChatAttributes->userAvatarUrl,
                'chat_title' => $userChatAttributes->userChatTitle,
                'chat_description' => $userChatAttributes->userChatSubtitle,
                'chat_description_link' => $userChatAttributes->userChatDescriptionLink,
                'chat_bubble_message' => $userChatAttributes->userChatBubbleMessage,
                'chat_bubble_class' => $userChatAttributes->userChatBubbleClass,
                'chat_avatar' => $userChatAttributes->chatAvatarUrl,
            ]);
        }

        $chat->refresh();
        DCodeChatCreatedForUser::dispatch($fromUser->chats()->where('chat_id', $chat->id)->first(), $fromUser); // @phpstan-ignore-line
        foreach ($toUsers as $toUser) {
            DCodeChatCreatedForUser::dispatch($toUser->chats()->where('chat_id', $chat->id)->first(), $toUser); // @phpstan-ignore-line
        }

        return $chat;
    }
}
