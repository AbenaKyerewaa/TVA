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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('attribute_name'); // e.g. "Carat Weight", "Diamond Clarity", "Ring Width"
            $table->string('attribute_value'); // e.g. "2.00 ct", "VVS1 / E Color", "2.5 mm"
            $table->timestamps();

            $table->index(['product_id', 'attribute_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
