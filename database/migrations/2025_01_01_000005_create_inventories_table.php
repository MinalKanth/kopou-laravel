<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique(); // one inventory row per product in this phase
            $table->unsignedInteger('stock_quantity')->default(0);   // physically on hand
            $table->unsignedInteger('reserved_quantity')->default(0); // held by unpaid/pending orders
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            // available_quantity = stock_quantity - reserved_quantity, computed in the model
            // rather than stored, so it can never drift out of sync.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
