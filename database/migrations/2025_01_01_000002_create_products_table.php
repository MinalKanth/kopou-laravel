<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name', 255);
            $table->string('slug', 280)->unique();
            $table->string('sku', 100)->unique();
            $table->string('brand', 150)->nullable();
            $table->string('origin', 150)->nullable(); // e.g. "Dibrugarh, Assam"

            // Money — never float. DECIMAL(12,2) supports up to 99,999,999,999.99.
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();

            $table->string('weight', 50)->nullable();
            $table->string('material', 150)->nullable();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('care_instructions')->nullable();
            $table->json('specifications')->nullable(); // [{"label":"Leaf grade","value":"FTGFOP1"}, ...]
            $table->json('badges')->nullable();          // ["BESTSELLER","ORGANIC",...]

            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('review_count')->default(0);

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('active');

            $table->string('seo_title', 255)->nullable();
            $table->string('seo_description', 320)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
            $table->index(['status', 'is_featured']);
            $table->index(['status', 'is_bestseller']);
            $table->index('category_id');
            $table->fullText(['name', 'short_description']); // MySQL full-text search, Section 19
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
