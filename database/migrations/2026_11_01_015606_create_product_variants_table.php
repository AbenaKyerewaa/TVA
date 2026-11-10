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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('name'); // e.g. "Size 7 / 18K Yellow Gold"
            $table->string('size')->nullable(); // e.g. "6", "7", "8", "45cm"
            $table->string('metal_type')->nullable(); // e.g. "18K Gold", "Platinum"
            $table->decimal('price', 10, 2)->nullable(); // variant specific override or null if same as product
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
