<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add index on view_count for popular products query optimization
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Index for ORDER BY view_count DESC queries
            $table->index(['is_active', 'view_count'], 'products_popular_index');
        });

        Schema::table('articles', function (Blueprint $table) {
            // Index for author queries if not exists
            if (!Schema::hasIndex('articles', 'articles_author_id_index')) {
                $table->index('author_id', 'articles_author_id_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_popular_index');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_author_id_index');
        });
    }
};
