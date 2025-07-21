<?php

namespace Dcodegroup\DCodeChat\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ChatEmailNotification extends Model
{
    use HasUlids;

    protected $table = 'chat_email_notifications';

    protected $fillable = [
        'user_id',
        'last_notified_at',
        'last_seen_at',
        'last_message_at',
    ];

    protected $casts = [
        'last_notified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'last_message_at' => 'datetime',
    ];
}
