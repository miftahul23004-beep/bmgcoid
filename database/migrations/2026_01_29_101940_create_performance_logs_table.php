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
        Schema::create('performance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('route_name')->nullable();
            $table->decimal('response_time', 10, 4); // seconds
            $table->integer('memory_usage')->nullable(); // bytes
            $table->integer('query_count')->nullable();
            $table->decimal('query_time', 10, 4)->nullable(); // seconds
            $table->string('method', 10)->default('GET');
            $table->integer('status_code')->default(200);
            $table->string('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('extra_data')->nullable();
            $table->timestamps();
            
            $table->index(['url', 'created_at']);
            $table->index(['route_name', 'created_at']);
            $table->index('response_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_logs');
    }
};
