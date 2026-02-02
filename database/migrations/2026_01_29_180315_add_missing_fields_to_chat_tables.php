<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            // Rename session_id to session_token for clarity
            $table->renameColumn('session_id', 'session_token');
            
            // Add missing fields
            $table->foreignId('assigned_to')->nullable()->after('operator_id')->constrained('users')->onDelete('set null');
            $table->timestamp('last_message_at')->nullable()->after('started_at');
            $table->timestamp('ended_at')->nullable()->after('closed_at');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            // Add user_id for operator messages
            $table->foreignId('user_id')->nullable()->after('operator_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->renameColumn('session_token', 'session_id');
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to', 'last_message_at', 'ended_at']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
