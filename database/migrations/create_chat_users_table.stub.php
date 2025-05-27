<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_users', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignUlid('chat_id')
                ->references('id')
                ->on('chats');
            $table->{config('dcode-chat.user_id_field_type')}('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on(config('dcode-chat.user_table'));

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
        Schema::dropIfExists('chat_users');
    }
};
