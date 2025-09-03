<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = $table->getColumns();
            // Check if the 'last_login_at' column already exists
            if (in_array('last_login_at', array_column($columns, 'name'))) {
                return; // Column already exists, no need to add it again
            }
            $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_at');
        });
    }
};
