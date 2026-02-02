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
        // Modify audit_type enum to include 'security'
        DB::statement("ALTER TABLE audit_results MODIFY COLUMN audit_type ENUM('performance', 'seo', 'security', 'both', 'all') DEFAULT 'both'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE audit_results MODIFY COLUMN audit_type ENUM('performance', 'seo', 'both') DEFAULT 'both'");
    }
};
