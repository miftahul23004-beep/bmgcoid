<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update type enum to include all form values
        DB::statement("ALTER TABLE inquiries MODIFY COLUMN `type` VARCHAR(50) NOT NULL DEFAULT 'general'");

        Schema::table('inquiries', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->after('message');
            $table->string('unit', 50)->nullable()->after('quantity');
            $table->string('source', 50)->nullable()->after('status');
            $table->string('ip_address', 45)->nullable()->after('source');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'unit', 'source', 'ip_address', 'user_agent']);
        });

        DB::statement("ALTER TABLE inquiries MODIFY COLUMN `type` ENUM('general', 'quote', 'product', 'support') NOT NULL DEFAULT 'general'");
    }
};
