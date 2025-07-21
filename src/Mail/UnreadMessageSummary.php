<?php

namespace Dcodegroup\DCodeChat\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnreadMessageSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $messages;

    public $user;

    public function __construct($messages, $user)
    {
        $this->messages = $messages;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject(config('dcode-chat.notification_email_subject', 'Unread Chat Messages'))
            ->view(config('dcode-chat.notification_email_view', 'dcode-chat::emails.unread_messages'), [
                'messages' => $this->messages,
                'user' => $this->user,
            ]);
    }
}
