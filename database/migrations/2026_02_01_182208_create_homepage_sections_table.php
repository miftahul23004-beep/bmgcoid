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
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // hero, stats, categories, featured, clients, etc.
            $table->string('name'); // Display name
            $table->string('name_en')->nullable(); // English name
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('bg_color')->default('white'); // white, gray-50, gray-100, primary, secondary, gradient
            $table->string('bg_gradient')->nullable(); // Custom gradient class
            $table->json('settings')->nullable(); // Additional settings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
