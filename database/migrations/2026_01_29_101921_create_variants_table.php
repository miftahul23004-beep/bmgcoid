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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->json('name'); // Translatable (e.g., "6mm x 12m", "8mm x 12m")
            $table->string('size')->nullable(); // e.g., "6mm", "8mm", "10mm"
            $table->string('thickness')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('weight')->nullable();
            $table->string('grade')->nullable(); // e.g., "SS400", "ASTM A36"
            $table->string('finish')->nullable(); // e.g., "Hot Rolled", "Cold Rolled"
            $table->decimal('price', 15, 2)->nullable();
            $table->string('price_unit')->default('kg');
            $table->integer('min_order')->default(1);
            $table->string('stock_status')->default('available'); // available, limited, out_of_stock, pre_order
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['product_id', 'is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
