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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->json('name'); // Translatable
            $table->json('short_description')->nullable();
            $table->json('description')->nullable();
            $table->string('slug')->unique();
            $table->decimal('base_price', 15, 2)->nullable();
            $table->string('price_unit')->default('kg'); // kg, pcs, meter, batang, lembar
            $table->boolean('price_on_request')->default(false);
            $table->string('featured_image')->nullable();
            $table->json('specifications')->nullable(); // {material, grade, standard, etc}
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('inquiry_count')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category_id', 'is_active', 'order']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['is_new', 'is_active']);
            $table->index(['is_bestseller', 'is_active']);
            $table->fullText(['sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
