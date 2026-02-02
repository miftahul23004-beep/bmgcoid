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
        // Modify ENUM to add more industry options
        DB::statement("ALTER TABLE clients MODIFY COLUMN industry ENUM('construction', 'manufacturing', 'infrastructure', 'automotive', 'shipbuilding', 'real_estate', 'oil_gas', 'mining', 'retail', 'hospitality', 'logistics', 'agriculture', 'other') DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE clients MODIFY COLUMN industry ENUM('construction', 'manufacturing', 'infrastructure', 'automotive', 'shipbuilding', 'other') DEFAULT 'other'");
    }
};
