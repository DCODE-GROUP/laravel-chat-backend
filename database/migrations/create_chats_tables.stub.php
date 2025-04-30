<?php

use Dcodegroup\LaravelChat\Models\Chat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->ulid('id');
            $table->morphs('chatable');
            $table->boolean('open')
                ->default(true);
            $table
                ->{config('laravel-chat.user_id_field_type')}('created_by')
                ->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on(config('laravel-chat.user_table'));
            $table
                ->{config('laravel-chat.user_id_field_type')}('updated_by')
                ->nullable();
            $table->foreignIdFor('updated_by')
                ->references('id')
                ->on(config('laravel-chat.user_table'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignUlid(Chat::class)
                ->references('id')
                ->on('chats');
            $table->mediumText('message');
            $table->{config('laravel-chat.users.user_id_field_type')}('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on(config('laravel-chat.user_table'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chat_users', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignUlid(Chat::class)
                ->references('id')
                ->on('chats');
            $table->{config('laravel-chat.users.user_id_field_type')}('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on(config('laravel-chat.user_table'));

            $table->string('user_name')
                ->nullable();
            $table->text('user_avatar')
                ->nullable();
            $table->text('chat_title')
                ->nullable();
            $table->text('chat_description')
                ->nullable();
            $table->text('chat_avatar')
                ->nullable();

            $table->timestamp('last_read_at')
                ->nullable();

            $table->boolean('has_new_messages')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_users');
    }
};
