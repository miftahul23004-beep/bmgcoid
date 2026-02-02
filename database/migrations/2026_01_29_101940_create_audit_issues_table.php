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
        Schema::create('audit_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_result_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['performance', 'seo', 'accessibility', 'best_practices']);
            $table->enum('severity', ['critical', 'warning', 'info']);
            $table->string('category');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('suggestion')->nullable();
            $table->string('element')->nullable(); // CSS selector or element identifier
            $table->decimal('impact_score', 5, 2)->nullable(); // Potential improvement
            $table->enum('status', ['open', 'fixed', 'ignored', 'wont_fix'])->default('open');
            $table->timestamp('fixed_at')->nullable();
            $table->timestamps();
            
            $table->index(['audit_result_id', 'type', 'severity']);
            $table->index(['status', 'severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_issues');
    }
};
