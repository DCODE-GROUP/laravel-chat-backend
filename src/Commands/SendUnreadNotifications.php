<?php

namespace Dcodegroup\DCodeChat\Commands;

use Dcodegroup\DCodeChat\Mail\UnreadMessageSummary;
use Dcodegroup\DCodeChat\Models\ChatEmailNotification;
use Dcodegroup\DCodeChat\Models\ChatMessage;
use Dcodegroup\DCodeChat\Models\ChatUser;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendUnreadNotifications extends Command
{
    protected $signature = 'chat:send-unread-notifications';

    protected $description = 'Send summary emails for unread chat messages older than 1 hour';

    public function handle(): int
    {
        $cutoff = Carbon::now()->subMinutes(config('dcode-chat.mins_to_notify', 60));

        $chatUsers = ChatUser::hasUnreadMessages()->has('user')->with('user')->get();

        $grouped = $chatUsers->groupBy('user_id');

        foreach ($grouped as $userId => $userChats) {
            $user = $userChats->first()->user;
            // Fetch or create the notification state record
            $notification = ChatEmailNotification::firstOrNew(['user_id' => $userId]);

            // Skip if the user logged in within the last hour
            if ($user->last_login_at && $user->last_login_at > $cutoff) {
                continue;
            }

            // Gather unread messages across all relevant chats
            $unreadMessages = ChatMessage::whereIn('chat_id', $userChats->pluck('chat_id'))
                ->with('chat')
                ->where('user_id', '!=', $userId)
                ->where(function ($query) use ($userChats) {
                    foreach ($userChats as $cu) {
                        $query->orWhere(function ($sub) use ($cu) {
                            $sub->where('chat_id', $cu->chat_id)
                                ->where('created_at', '>', $cu->last_read_at ?? now()->subYears(50));
                        });
                    }
                })
                ->get();

            if ($unreadMessages->isEmpty()) {
                continue;
            }

            $latestMessageTime = $unreadMessages->max('created_at');

            // Check: don't send if already notified for this message batch
            if (
                $notification->last_notified_at &&
                $notification->last_notified_at >= $latestMessageTime
            ) {
                continue;
            }

            Mail::to($user->email)->queue(new UnreadMessageSummary($unreadMessages, $user));

            $notification->last_message_at = $latestMessageTime;
            $notification->last_notified_at = now();
            $notification->save();

            $this->info("Sent unread message email to user ID: $userId");
        }

        return Command::SUCCESS;
    }
}
