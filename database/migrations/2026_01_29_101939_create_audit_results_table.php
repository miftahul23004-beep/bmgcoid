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
        Schema::create('audit_results', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('page_type')->nullable(); // home, product, article, category
            $table->enum('audit_type', ['performance', 'seo', 'both'])->default('both');
            
            // Performance scores (0-100)
            $table->integer('performance_score')->nullable();
            $table->integer('seo_score')->nullable();
            $table->integer('accessibility_score')->nullable();
            $table->integer('best_practices_score')->nullable();
            
            // Core Web Vitals
            $table->integer('fcp')->nullable(); // First Contentful Paint (ms)
            $table->integer('lcp')->nullable(); // Largest Contentful Paint (ms)
            $table->decimal('cls', 5, 4)->nullable(); // Cumulative Layout Shift
            $table->integer('tti')->nullable(); // Time to Interactive (ms)
            $table->integer('tbt')->nullable(); // Total Blocking Time (ms)
            $table->integer('speed_index')->nullable();
            
            // Additional metrics
            $table->integer('page_size')->nullable(); // bytes
            $table->integer('request_count')->nullable();
            $table->decimal('load_time', 8, 2)->nullable(); // seconds
            
            $table->json('raw_data')->nullable(); // Full Lighthouse/PageSpeed data
            $table->text('notes')->nullable();
            $table->enum('source', ['manual', 'scheduled', 'api'])->default('manual');
            $table->timestamps();
            
            $table->index(['url', 'created_at']);
            $table->index(['audit_type', 'created_at']);
            $table->index(['page_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_results');
    }
};
