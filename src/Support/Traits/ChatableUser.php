<?php

namespace Dcodegroup\DCodeChat\Support\Traits;

use Dcodegroup\DCodeChat\Models\ChatUser;

trait ChatableUser
{
    /**
     * Get the chats for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function chats()
    {
        // Which chats is the user part of from chat_users pivot table
        // This assumes that the pivot table is named 'chat_users' and has 'user_id' and 'chat_id' columns
        return $this->belongsToMany(
            config('dcode-chat.dcode_chat_model'),
            'chat_users',
            'user_id',
            'chat_id'
        )->using(ChatUser::class)->withPivot([
            'user_name',
            'user_avatar',
            'chat_title',
            'chat_description',
            'chat_avatar',
            'last_read_at',
            'has_new_messages',
        ])->withTimestamps();
    }

    /**
     * Get the messages for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function messages()
    {
        return $this->hasMany(config('dcode-chat.models.message'));
    }
}
