<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignUlid('chat_id')
                ->references('id')
                ->on('chats');
            $table->mediumText('message');
            $table->{config('dcode-chat.user_id_field_type')}('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on(config('dcode-chat.user_table'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
