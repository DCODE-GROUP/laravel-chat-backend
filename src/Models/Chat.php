<?php

namespace Dcodegroup\DCodeChat\Models;

use Dcodegroup\DCodeChat\Support\Traits\LastModifiedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method Builder byRelation(string $chatableType, string $chatableId)
 */
class Chat extends Model
{
    use HasFactory;
    use HasUlids;
    use LastModifiedBy;
    use SoftDeletes;

    protected $fillable = [
        'chatable_type',
        'chatable_id',
        'open',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'open' => 'boolean',
    ];

    public function chatable(): MorphTo
    {
        return $this->morphTo('chatable')->withTrashed();
    }

    public function users()
    {
        // Which chats is the user part of from chat_users pivot table
        // This assumes that the pivot table is named 'chat_users' and has 'user_id' and 'chat_id' columns
        return $this->belongsToMany(
            config('dcode-chat.user_model'),
            'chat_users',
            'chat_id',
            'user_id'
        )->using(ChatUser::class)->withPivot([
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
        ])->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function scopeByRelation(Builder $query, string $chatableType, string $chatableId): void
    {
        $query->where('chatable_type', $chatableType)
            ->where('chatable_id', $chatableId);
    }
}
