<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class, // 12 curated products used across the storefront previews
        ]);

        // Pad the catalog to ~30 products (Section 38) with believable
        // Assam-themed generated listings, each still getting a
        // placeholder image and an inventory row so nothing 500s.
        Product::factory()
            ->count(18)
            ->create()
            ->each(function (Product $product) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => '/images/placeholder.jpg',
                    'alt_text' => $product->name,
                    'sort_order' => 0,
                ]);

                Inventory::create([
                    'product_id' => $product->id,
                    'stock_quantity' => fake()->numberBetween(5, 80),
                    'reserved_quantity' => 0,
                    'low_stock_threshold' => 6,
                ]);
            });
    }
}
