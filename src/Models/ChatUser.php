<?php

namespace Dcodegroup\DCodeChat\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $chat_id
 * @property string $user_id
 * @property string|null $user_name
 * @property string|null $user_avatar
 * @property string|null $chat_title
 * @property string|null $chat_description
 * @property string|null $chat_description_link
 * @property string|null $chat_bubble_message
 * @property string|null $chat_bubble_class
 * @property string|null $chat_avatar
 * @property \Illuminate\Support\Carbon|null $last_read_at
 * @property bool $has_new_messages
 */
class ChatUser extends Pivot
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $table = 'chat_users';

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

    public function scopeHasUnreadMessages(Builder $query)
    {
        $query->whereHas('chat', function ($query) {
            $query->whereNull('deleted_at');
        })
            ->where('has_new_messages', true)
            ->where(function ($query) {
                $query->whereNull('last_read_at')
                    ->orWhere('last_read_at', '<', function ($subQuery) {
                        $subQuery->select('chat_messages.created_at')
                            ->from('chat_messages')
                            ->whereColumn('chat_messages.chat_id', 'chat_users.chat_id')
                            ->where('chat_messages.user_id', '!=', 'chat_users.user_id')
                            ->orderBy('chat_messages.created_at', 'desc')
                            ->limit(1);
                    });

            });
    }
}
