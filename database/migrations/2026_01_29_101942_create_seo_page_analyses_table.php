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
        Schema::create('seo_page_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->string('page_type')->nullable();
            
            // Meta analysis
            $table->string('meta_title', 255)->nullable();
            $table->integer('meta_title_length')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('meta_description_length')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('has_robots_meta')->default(false);
            
            // Content analysis
            $table->string('h1_tag')->nullable();
            $table->integer('h1_count')->default(0);
            $table->integer('h2_count')->default(0);
            $table->integer('h3_count')->default(0);
            $table->integer('word_count')->default(0);
            $table->integer('image_count')->default(0);
            $table->integer('images_without_alt')->default(0);
            
            // Links
            $table->integer('internal_links_count')->default(0);
            $table->integer('external_links_count')->default(0);
            $table->integer('broken_links_count')->default(0);
            
            // Technical
            $table->boolean('has_structured_data')->default(false);
            $table->boolean('has_og_tags')->default(false);
            $table->boolean('has_twitter_cards')->default(false);
            $table->boolean('is_mobile_friendly')->default(true);
            $table->boolean('has_ssl')->default(true);
            
            // Score
            $table->integer('seo_score')->default(0);
            $table->json('issues')->nullable();
            $table->json('suggestions')->nullable();
            
            $table->timestamp('last_analyzed_at')->nullable();
            $table->timestamps();
            
            $table->index(['page_type', 'seo_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_page_analyses');
    }
};
