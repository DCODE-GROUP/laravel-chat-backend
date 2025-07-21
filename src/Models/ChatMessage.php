<?php

namespace Dcodegroup\DCodeChat\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'chat_id',
        'message',
        'user_id',
    ];

    protected $appends = [
        'user_attributes',
    ];

    public function userAttributes(): Attribute
    {
        return Attribute::make(
            function () {

                return $this->loadMissing('chat')->chat->users()
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
