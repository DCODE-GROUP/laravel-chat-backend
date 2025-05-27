<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->nullableMorphs('chatable');
            $table->boolean('open')
                ->default(true);
            $table
                ->{config('dcode-chat.user_id_field_type')}('created_by')
                ->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on(config('dcode-chat.user_table'));
            $table
                ->{config('dcode-chat.user_id_field_type')}('updated_by')
                ->nullable();
            $table->foreign('updated_by')
                ->references('id')
                ->on(config('dcode-chat.user_table'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
