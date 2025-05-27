<?php

namespace Dcodegroup\DCodeChat\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'chat_id',
        'message',
        'user_id',
    ];

    protected $appends = [
        'is_me',
        'user_attributes',
    ];

    public function isMe(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user_id === auth()?->id(),
        );
    }

    public function userAttributes(): Attribute
    {
        return Attribute::make(
            function () {

                return $this->chat->users()
                    ->where('user_id', $this->user_id)
                    ->first()?->pivot;
            });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('dcode-chat.user_model'), 'user_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
