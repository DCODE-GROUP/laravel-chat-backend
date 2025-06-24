<?php

namespace Dcodegroup\DCodeChat\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatUser extends Pivot
{
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'chat_id',
        'user_id',
        'user_name',
        'user_avatar',
        'chat_title',
        'chat_description',
        'chat_description_link',
        'chat_bubble_message',
        'chat_bubble_class',
        'chat_avatar',
        'last_read_at',
        'has_new_messages',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'has_new_messages' => 'boolean',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('dcode-chat.user_model'));
    }
}
