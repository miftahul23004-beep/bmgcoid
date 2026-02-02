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
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->json('title'); // Translatable
            $table->enum('type', ['datasheet', 'certificate', 'manual', 'brochure', 'other']);
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->integer('file_size'); // In bytes
            $table->integer('download_count')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            
            $table->index(['product_id', 'type', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_documents');
    }
};
