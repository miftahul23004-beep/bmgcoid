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
        Schema::create('scheduled_audits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('urls'); // Array of URLs to audit
            $table->enum('frequency', ['hourly', 'daily', 'weekly', 'monthly']);
            $table->string('time')->nullable(); // e.g., "02:00"
            $table->string('day_of_week')->nullable(); // For weekly
            $table->integer('day_of_month')->nullable(); // For monthly
            $table->boolean('include_performance')->default(true);
            $table->boolean('include_seo')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_active', 'next_run_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_audits');
    }
};
