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
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            
            // Multi-language content
            $table->string('title_id'); // Indonesian title
            $table->string('title_en')->nullable(); // English title
            $table->text('subtitle_id')->nullable(); // Indonesian subtitle
            $table->text('subtitle_en')->nullable(); // English subtitle
            
            // Media
            $table->string('image')->nullable(); // Background image
            $table->string('mobile_image')->nullable(); // Mobile-specific image (optional)
            
            // Styling
            $table->string('gradient_class')->default('from-primary-900/95 via-primary-800/90 to-primary-700/85');
            $table->string('text_color')->default('white');
            
            // CTA Buttons
            $table->string('primary_button_text_id')->nullable();
            $table->string('primary_button_text_en')->nullable();
            $table->string('primary_button_url')->nullable();
            $table->string('secondary_button_text_id')->nullable();
            $table->string('secondary_button_text_en')->nullable();
            $table->string('secondary_button_url')->nullable();
            
            // Badge/Label
            $table->string('badge_text_id')->nullable();
            $table->string('badge_text_en')->nullable();
            
            // Settings
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamp('start_date')->nullable(); // Schedule start
            $table->timestamp('end_date')->nullable(); // Schedule end
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
