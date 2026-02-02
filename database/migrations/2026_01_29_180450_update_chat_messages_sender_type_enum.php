<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum to include 'system' type
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN sender_type ENUM('visitor', 'ai', 'operator', 'system') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN sender_type ENUM('visitor', 'ai', 'operator') NOT NULL");
    }
};
