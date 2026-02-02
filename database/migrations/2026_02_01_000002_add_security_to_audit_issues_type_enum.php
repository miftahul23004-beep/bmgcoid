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
        // Modify type enum in audit_issues to include 'security'
        DB::statement("ALTER TABLE audit_issues MODIFY COLUMN type ENUM('performance', 'seo', 'accessibility', 'best_practices', 'security') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE audit_issues MODIFY COLUMN type ENUM('performance', 'seo', 'accessibility', 'best_practices') NOT NULL");
    }
};
