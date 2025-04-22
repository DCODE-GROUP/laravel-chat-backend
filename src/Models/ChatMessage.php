<?php

namespace Dcodegroup\LaravelChat\Models;

use Dcodegroup\LaravelChat\Support\Traits\LastModifiedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use LastModifiedBy;
    use SoftDeletes;

    protected $fillable = [
        'chat_id',
        'message',
        'user_id',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
