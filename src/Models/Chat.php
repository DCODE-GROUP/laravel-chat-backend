<?php

namespace Dcodegroup\LaravelChat\Models;

use Dcodegroup\LaravelChat\Support\Traits\LastModifiedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method Builder byRelation(string $chattableType, string $chattableId)
 */
class Chat extends Model
{
    use LastModifiedBy;
    use SoftDeletes;

    protected $fillable = [
        'chattable_type',
        'chattable_id',
        'open',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'open' => 'boolean',
    ];

    public function chattable(): MorphTo
    {
        return $this->morphTo('chattable');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    public function scopeByRelation(Builder $query, string $chattableType, string $chattableId): void
    {
        $query->where('chattable_type', $chattableType)
            ->where('chattable_id', $chattableId);
    }
}
