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
        Schema::table('settings', function (Blueprint $table) {
            // Drop the old unique constraint on 'key' only
            $table->dropUnique(['key']);
            
            // Add new unique constraint on 'group' + 'key' combination
            $table->unique(['group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique(['group', 'key']);
            
            // Restore the old unique constraint on 'key' only
            $table->unique('key');
        });
    }
};
