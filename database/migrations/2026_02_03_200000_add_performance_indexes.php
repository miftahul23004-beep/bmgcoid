<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Run the migrations.
     * These indexes improve admin panel and frontend query performance
     */
    public function up(): void
    {
        // Products - additional indexes for admin filtering
        Schema::table('products', function (Blueprint $table) {
            if (!$this->indexExists('products', 'products_created_at_index')) {
                $table->index('created_at');
            }
            if (!$this->indexExists('products', 'products_updated_at_index')) {
                $table->index('updated_at');
            }
            if (!$this->indexExists('products', 'products_view_count_index')) {
                $table->index('view_count');
            }
        });

        // Articles - additional indexes
        Schema::table('articles', function (Blueprint $table) {
            if (!$this->indexExists('articles', 'articles_author_id_index')) {
                $table->index('author_id');
            }
            if (!$this->indexExists('articles', 'articles_created_at_index')) {
                $table->index('created_at');
            }
            if (!$this->indexExists('articles', 'articles_view_count_index')) {
                $table->index('view_count');
            }
        });

        // Categories - ensure is_featured is indexed
        Schema::table('categories', function (Blueprint $table) {
            if (!$this->indexExists('categories', 'categories_is_featured_index')) {
                $table->index('is_featured');
            }
            if (!$this->indexExists('categories', 'categories_is_active_index')) {
                $table->index('is_active');
            }
        });

        // Hero slides - for active/scheduled filtering
        Schema::table('hero_slides', function (Blueprint $table) {
            if (!$this->indexExists('hero_slides', 'hero_slides_is_active_index')) {
                $table->index('is_active');
            }
            if (!$this->indexExists('hero_slides', 'hero_slides_order_index')) {
                $table->index('order');
            }
            if (!$this->indexExists('hero_slides', 'hero_slides_is_active_order_index')) {
                $table->index(['is_active', 'order']);
            }
            if (!$this->indexExists('hero_slides', 'hero_slides_start_date_end_date_index')) {
                $table->index(['start_date', 'end_date']);
            }
        });

        // Clients - for featured display
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (!$this->indexExists('clients', 'clients_is_active_is_featured_order_index')) {
                    $table->index(['is_active', 'is_featured', 'order']);
                }
            });
        }

        // Testimonials - for featured display
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                if (!$this->indexExists('testimonials', 'testimonials_is_active_is_featured_order_index')) {
                    $table->index(['is_active', 'is_featured', 'order']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if ($this->indexExists('products', 'products_created_at_index')) {
                $table->dropIndex(['created_at']);
            }
            if ($this->indexExists('products', 'products_updated_at_index')) {
                $table->dropIndex(['updated_at']);
            }
            if ($this->indexExists('products', 'products_view_count_index')) {
                $table->dropIndex(['view_count']);
            }
        });

        Schema::table('articles', function (Blueprint $table) {
            if ($this->indexExists('articles', 'articles_author_id_index')) {
                $table->dropIndex(['author_id']);
            }
            if ($this->indexExists('articles', 'articles_created_at_index')) {
                $table->dropIndex(['created_at']);
            }
            if ($this->indexExists('articles', 'articles_view_count_index')) {
                $table->dropIndex(['view_count']);
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if ($this->indexExists('categories', 'categories_is_featured_index')) {
                $table->dropIndex(['is_featured']);
            }
            if ($this->indexExists('categories', 'categories_is_active_index')) {
                $table->dropIndex(['is_active']);
            }
        });

        Schema::table('hero_slides', function (Blueprint $table) {
            if ($this->indexExists('hero_slides', 'hero_slides_is_active_index')) {
                $table->dropIndex(['is_active']);
            }
            if ($this->indexExists('hero_slides', 'hero_slides_order_index')) {
                $table->dropIndex(['order']);
            }
            if ($this->indexExists('hero_slides', 'hero_slides_is_active_order_index')) {
                $table->dropIndex(['is_active', 'order']);
            }
            if ($this->indexExists('hero_slides', 'hero_slides_start_date_end_date_index')) {
                $table->dropIndex(['start_date', 'end_date']);
            }
        });

        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if ($this->indexExists('clients', 'clients_is_active_is_featured_order_index')) {
                    $table->dropIndex(['is_active', 'is_featured', 'order']);
                }
            });
        }

        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                if ($this->indexExists('testimonials', 'testimonials_is_active_is_featured_order_index')) {
                    $table->dropIndex(['is_active', 'is_featured', 'order']);
                }
            });
        }
    }
};
