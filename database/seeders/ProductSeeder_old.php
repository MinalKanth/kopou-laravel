<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->products() as $data) {
            $category = Category::where('slug', $data['category_slug'])->firstOrFail();

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'sku' => $data['sku'],
                    'brand' => $data['brand'],
                    'origin' => $data['origin'],
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'weight' => $data['weight'],
                    'material' => $data['material'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'care_instructions' => $data['care_instructions'],
                    'specifications' => $data['specifications'],
                    'badges' => $data['badges'],
                    'rating' => $data['rating'],
                    'review_count' => $data['review_count'],
                    'is_featured' => true,
                    'is_bestseller' => in_array('BESTSELLER', $data['badges'], true),
                    'status' => 'active',
                    'seo_title' => $data['name'].' | KOPOU — Authentic Assam',
                    'seo_description' => $data['short_description'],
                ]
            );

            $product->images()->delete();
            foreach ($data['gallery'] as $i => $path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'alt_text' => $data['name'],
                    'sort_order' => $i,
                ]);
            }

            $product->variants()->delete();
            foreach ($data['variants'] as $i => $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'label' => $variant['label'],
                    'sku' => $data['sku'].'-'.strtoupper(str_replace(' ', '', $variant['label'])),
                    'price' => $variant['price'],
                    'sale_price' => $variant['sale_price'],
                    'sort_order' => $i,
                ]);
            }

            Inventory::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'stock_quantity' => $data['stock_quantity'],
                    'reserved_quantity' => 0,
                    'low_stock_threshold' => 6,
                ]
            );
        }
    }

    /**
     * Same 12 products used in Phase 3's DummyCatalog, ported here so the
     * storefront looks identical once switched over to the database.
     */
    private function products(): array
    {
        return [
            ['slug' => 'premium-assam-orthodox-black-tea', 'name' => 'Premium Assam Orthodox Black Tea', 'category_slug' => 'assam-tea', 'origin' => 'Dibrugarh, Assam', 'brand' => 'Borbheta Tea Estate', 'sku' => 'KPU-TEA-0001', 'price' => 799.00, 'sale_price' => 649.00, 'rating' => 4.8, 'review_count' => 126, 'stock_quantity' => 42, 'weight' => '250g', 'material' => null, 'badges' => ['BESTSELLER', 'ASSAM ORIGINAL'], 'gallery' => ['/images/products/tea-orthodox.jpg', '/images/products/tea-orthodox-2.jpg', '/images/products/tea-orthodox-3.jpg'], 'short_description' => 'Full-leaf second-flush orthodox tea, hand-picked from a single Dibrugarh estate.', 'description' => 'A second-flush orthodox black tea from a single estate in Dibrugarh, hand-picked and rolled in small batches. Malty, full-bodied, with the bright finish orthodox Assam tea is known for.', 'specifications' => ['Leaf grade' => 'FTGFOP1', 'Flush' => 'Second flush', 'Processing' => 'Orthodox'], 'care_instructions' => 'Store in an airtight container away from direct sunlight and moisture.', 'variants' => [['label' => '100g', 'price' => 349.00, 'sale_price' => null], ['label' => '250g', 'price' => 799.00, 'sale_price' => 649.00], ['label' => '500g', 'price' => 1499.00, 'sale_price' => 1299.00]]],
            ['slug' => 'traditional-gamosa-handwoven', 'name' => 'Traditional Handwoven Gamosa', 'category_slug' => 'handloom-textiles', 'origin' => 'Sualkuchi, Assam', 'brand' => 'Sualkuchi Weavers Collective', 'sku' => 'KPU-HLM-0002', 'price' => 599.00, 'sale_price' => null, 'rating' => 4.9, 'review_count' => 84, 'stock_quantity' => 6, 'weight' => '150g', 'material' => 'Handspun cotton', 'badges' => ['HANDCRAFTED'], 'gallery' => ['/images/products/gamosa.jpg', '/images/products/gamosa-2.jpg'], 'short_description' => 'Cotton gamosa woven on a traditional loom, red motif on white ground.', 'description' => 'Handwoven on a traditional pit loom by a weaver in Sualkuchi, this gamosa carries the classic red geometric motif on an unbleached cotton ground.', 'specifications' => ['Dimensions' => '96in x 16in', 'Weave' => 'Plain weave, hand-thrown shuttle'], 'care_instructions' => 'Hand wash in cold water for the first wash; line dry in shade.', 'variants' => []],
            ['slug' => 'muga-silk-stole-sualkuchi', 'name' => 'Muga Silk Stole', 'category_slug' => 'handloom-textiles', 'origin' => 'Sualkuchi, Assam', 'brand' => 'Sualkuchi Weavers Collective', 'sku' => 'KPU-HLM-0003', 'price' => 4200.00, 'sale_price' => 3780.00, 'rating' => 4.7, 'review_count' => 39, 'stock_quantity' => 11, 'weight' => '120g', 'material' => 'Muga silk', 'badges' => ['PREMIUM', 'HANDCRAFTED'], 'gallery' => ['/images/products/muga-stole.jpg', '/images/products/muga-stole-2.jpg'], 'short_description' => 'Natural golden-sheen muga silk, hand-woven by Sualkuchi artisans.', 'description' => "Muga silk is unique to Assam and unbleached — the golden sheen is natural to the fibre, not dyed.", 'specifications' => ['Dimensions' => '80in x 26in', 'Silk type' => 'Muga (Antheraea assamensis)'], 'care_instructions' => 'Dry clean recommended.', 'variants' => []],
            ['slug' => 'assam-joha-rice-1kg', 'name' => 'Assam Joha Rice', 'category_slug' => 'food-delicacies', 'origin' => 'Nagaon, Assam', 'brand' => 'Brahmaputra Valley Farmers', 'sku' => 'KPU-FOD-0004', 'price' => 349.00, 'sale_price' => null, 'rating' => 4.6, 'review_count' => 58, 'stock_quantity' => 73, 'weight' => '1kg', 'material' => null, 'badges' => ['ORGANIC'], 'gallery' => ['/images/products/joha-rice.jpg'], 'short_description' => 'Short-grain, naturally aromatic rice grown in the Brahmaputra floodplain.', 'description' => 'Joha is a short-grain aromatic rice variety native to Assam, traditionally used for festive pulao and payash.', 'specifications' => ['Grain type' => 'Short-grain, aromatic', 'Farming' => 'Pesticide-free'], 'care_instructions' => 'Store in a cool, dry, airtight container.', 'variants' => [['label' => '1kg', 'price' => 349.00, 'sale_price' => null], ['label' => '5kg', 'price' => 1599.00, 'sale_price' => 1449.00]]],
            ['slug' => 'bell-metal-traditional-bowl', 'name' => 'Bell Metal Traditional Bowl (Kahi)', 'category_slug' => 'handicrafts', 'origin' => 'Sarthebari, Assam', 'brand' => 'Sarthebari Bell Metal Guild', 'sku' => 'KPU-CFT-0005', 'price' => 1450.00, 'sale_price' => 1250.00, 'rating' => 4.8, 'review_count' => 22, 'stock_quantity' => 15, 'weight' => '380g', 'material' => 'Bell metal (kahi)', 'badges' => ['HANDCRAFTED'], 'gallery' => ['/images/products/bell-metal-bowl.jpg', '/images/products/bell-metal-bowl-2.jpg'], 'short_description' => 'Hand-hammered bell metal bowl from the artisan cluster of Sarthebari.', 'description' => "Hand-hammered by bell-metal craftsmen in Sarthebari, Assam's traditional metalwork town.", 'specifications' => ['Diameter' => '5.5in', 'Alloy' => 'Copper-tin bell metal'], 'care_instructions' => 'Hand wash only.', 'variants' => []],
            ['slug' => 'wild-forest-honey-500g', 'name' => 'Assam Wild Forest Honey', 'category_slug' => 'food-delicacies', 'origin' => 'Kaziranga fringe villages, Assam', 'brand' => 'Kaziranga Forest Collective', 'sku' => 'KPU-FOD-0006', 'price' => 549.00, 'sale_price' => null, 'rating' => 4.7, 'review_count' => 91, 'stock_quantity' => 4, 'weight' => '500g', 'material' => null, 'badges' => ['ORGANIC', 'LIMITED'], 'gallery' => ['/images/products/honey.jpg'], 'short_description' => 'Raw, unprocessed honey foraged from forest-fringe hives near Kaziranga.', 'description' => 'Raw honey foraged from wild and semi-wild hives in villages bordering Kaziranga National Park.', 'specifications' => ['Processing' => 'Raw, unheated, unfiltered'], 'care_instructions' => 'Store at room temperature.', 'variants' => []],
            ['slug' => 'bamboo-handcrafted-basket', 'name' => 'Bamboo Handcrafted Storage Basket', 'category_slug' => 'handicrafts', 'origin' => 'Barpeta, Assam', 'brand' => 'Barpeta Cane & Bamboo Cluster', 'sku' => 'KPU-CFT-0007', 'price' => 899.00, 'sale_price' => 749.00, 'rating' => 4.5, 'review_count' => 17, 'stock_quantity' => 28, 'weight' => '620g', 'material' => 'Split bamboo cane', 'badges' => ['HANDCRAFTED', 'NEW'], 'gallery' => ['/images/products/bamboo-basket.jpg'], 'short_description' => 'Split-cane basket, tightly woven for daily use, from Barpeta craftsmen.', 'description' => 'Tightly woven from split bamboo cane by craftsmen in Barpeta.', 'specifications' => ['Dimensions' => '12in x 12in x 10in'], 'care_instructions' => 'Wipe clean with a dry cloth.', 'variants' => []],
            ['slug' => 'premium-assam-tea-gift-box', 'name' => 'Premium Assam Tea Gift Box', 'category_slug' => 'gift-boxes', 'origin' => 'Assam, India', 'brand' => 'KOPOU Curated', 'sku' => 'KPU-GFT-0008', 'price' => 1899.00, 'sale_price' => 1599.00, 'rating' => 4.9, 'review_count' => 63, 'stock_quantity' => 20, 'weight' => '600g', 'material' => null, 'badges' => ['BESTSELLER', 'PREMIUM'], 'gallery' => ['/images/products/tea-gift-box.jpg', '/images/products/tea-gift-box-2.jpg'], 'short_description' => 'Four single-estate teas presented in a hand-finished wooden box.', 'description' => 'Four single-estate teas presented in a hand-finished bamboo-inlay wooden box.', 'specifications' => ['Contents' => '4 x 50g single-estate teas'], 'care_instructions' => 'Store away from direct sunlight.', 'variants' => []],
            ['slug' => 'eri-silk-shawl', 'name' => 'Eri Silk Shawl', 'category_slug' => 'handloom-textiles', 'origin' => 'Dhemaji, Assam', 'brand' => 'Dhemaji Eri Weavers', 'sku' => 'KPU-HLM-0009', 'price' => 2600.00, 'sale_price' => 2340.00, 'rating' => 4.6, 'review_count' => 14, 'stock_quantity' => 9, 'weight' => '340g', 'material' => 'Eri silk', 'badges' => ['HANDCRAFTED'], 'gallery' => ['/images/products/eri-shawl.jpg'], 'short_description' => "Warm, matte-textured eri silk shawl, woven by Dhemaji weavers.", 'description' => "Eri silk is Assam's 'peace silk', spun from silk staple rather than reeled from live cocoons.", 'specifications' => ['Dimensions' => '84in x 32in'], 'care_instructions' => 'Dry clean or gentle hand wash in cold water.', 'variants' => []],
            ['slug' => 'assamese-traditional-pickle', 'name' => 'Assamese Bamboo Shoot Pickle', 'category_slug' => 'food-delicacies', 'origin' => 'Jorhat, Assam', 'brand' => 'Jorhat Home Kitchens', 'sku' => 'KPU-FOD-0010', 'price' => 279.00, 'sale_price' => null, 'rating' => 4.4, 'review_count' => 33, 'stock_quantity' => 55, 'weight' => '250g', 'material' => null, 'badges' => [], 'gallery' => ['/images/products/pickle.jpg'], 'short_description' => 'Tangy fermented bamboo shoot pickle, made in small home-kitchen batches.', 'description' => 'A tangy, pungent bamboo shoot pickle made in small batches by home kitchens in Jorhat.', 'specifications' => ['Main ingredient' => 'Fermented bamboo shoot'], 'care_instructions' => 'Refrigerate after opening.', 'variants' => []],
            ['slug' => 'traditional-bamboo-lamp', 'name' => 'Traditional Bamboo Lamp', 'category_slug' => 'handicrafts', 'origin' => 'Barpeta, Assam', 'brand' => 'Barpeta Cane & Bamboo Cluster', 'sku' => 'KPU-CFT-0011', 'price' => 1290.00, 'sale_price' => 1090.00, 'rating' => 4.5, 'review_count' => 9, 'stock_quantity' => 13, 'weight' => '450g', 'material' => 'Bamboo, cotton wiring', 'badges' => ['HANDCRAFTED', 'NEW'], 'gallery' => ['/images/products/bamboo-lamp.jpg'], 'short_description' => 'Hand-built bamboo table lamp with a soft, latticed glow.', 'description' => 'A table lamp hand-built from split bamboo strips over a wire frame.', 'specifications' => ['Dimensions' => '10in x 10in x 14in'], 'care_instructions' => 'Dust with a dry brush.', 'variants' => []],
            ['slug' => 'assam-green-tea-organic', 'name' => 'Assam Organic Green Tea', 'category_slug' => 'assam-tea', 'origin' => 'Golaghat, Assam', 'brand' => 'Borbheta Tea Estate', 'sku' => 'KPU-TEA-0012', 'price' => 549.00, 'sale_price' => null, 'rating' => 4.5, 'review_count' => 47, 'stock_quantity' => 31, 'weight' => '100g', 'material' => null, 'badges' => ['ORGANIC'], 'gallery' => ['/images/products/tea-green.jpg'], 'short_description' => 'Lightly oxidised organic green tea from a certified Golaghat estate.', 'description' => 'A lightly oxidised green tea from a certified-organic Golaghat estate.', 'specifications' => ['Certification' => 'India Organic (NPOP)'], 'care_instructions' => 'Store airtight, away from light.', 'variants' => [['label' => '100g', 'price' => 549.00, 'sale_price' => null], ['label' => '250g', 'price' => 1199.00, 'sale_price' => null]]],
        ];
    }
}
