<?php

namespace App\Data;

/**
 * Static stand-in for the catalog until Phase 4 introduces
 * App\Models\Product / App\Models\Category backed by MySQL.
 *
 * Column names mirror the planned `products`, `product_images`,
 * `product_variants` and `categories` tables (Section 16 of the brief)
 * so HomeController / ProductController can later swap these static
 * methods for Eloquent queries without any Blade template changing.
 */
class DummyCatalog
{
    public static function categories(): array
    {
        return [
            ['id' => 1, 'name' => 'Assam Tea', 'slug' => 'assam-tea', 'product_count' => 48, 'image' => '/images/categories/tea.jpg'],
            ['id' => 2, 'name' => 'Handloom & Textiles', 'slug' => 'handloom-textiles', 'product_count' => 36, 'image' => '/images/categories/handloom.jpg'],
            ['id' => 3, 'name' => 'Handicrafts', 'slug' => 'handicrafts', 'product_count' => 29, 'image' => '/images/categories/handicrafts.jpg'],
            ['id' => 4, 'name' => 'Food & Delicacies', 'slug' => 'food-delicacies', 'product_count' => 41, 'image' => '/images/categories/food.jpg'],
            ['id' => 5, 'name' => 'Gift Boxes', 'slug' => 'gift-boxes', 'product_count' => 18, 'image' => '/images/categories/gifts.jpg'],
        ];
    }

    public static function products(): array
    {
        return [
            [
                'id' => 1, 'slug' => 'premium-assam-orthodox-black-tea', 'name' => 'Premium Assam Orthodox Black Tea',
                'category' => 'Assam Tea', 'category_slug' => 'assam-tea', 'origin' => 'Dibrugarh, Assam',
                'brand' => 'Borbheta Tea Estate', 'sku' => 'KPU-TEA-0001', 'price' => 799.00, 'sale_price' => 649.00,
                'rating' => 4.8, 'review_count' => 126, 'stock_quantity' => 42, 'weight' => '250g', 'material' => null,
                'badges' => ['BESTSELLER', 'ASSAM ORIGINAL'], 'image' => '/images/products/tea-orthodox.jpg',
                'gallery' => ['/images/products/tea-orthodox.jpg', '/images/products/tea-orthodox-2.jpg', '/images/products/tea-orthodox-3.jpg'],
                'short_description' => 'Full-leaf second-flush orthodox tea, hand-picked from a single Dibrugarh estate.',
                'description' => "A second-flush orthodox black tea from a single estate in Dibrugarh, hand-picked and rolled in small batches. Malty, full-bodied, with the bright finish orthodox Assam tea is known for. Best brewed 3-4 minutes in fresh-boiled water.",
                'specifications' => ['Leaf grade' => 'FTGFOP1', 'Flush' => 'Second flush', 'Processing' => 'Orthodox', 'Caffeine' => 'Contains caffeine', 'Shelf life' => '18 months from packing'],
                'care_instructions' => 'Store in an airtight container away from direct sunlight and moisture.',
                'variants' => [
                    ['label' => '100g', 'price' => 349.00, 'sale_price' => null],
                    ['label' => '250g', 'price' => 799.00, 'sale_price' => 649.00],
                    ['label' => '500g', 'price' => 1499.00, 'sale_price' => 1299.00],
                ],
            ],
            [
                'id' => 2, 'slug' => 'traditional-gamosa-handwoven', 'name' => 'Traditional Handwoven Gamosa',
                'category' => 'Handloom & Textiles', 'category_slug' => 'handloom-textiles', 'origin' => 'Sualkuchi, Assam',
                'brand' => 'Sualkuchi Weavers Collective', 'sku' => 'KPU-HLM-0002', 'price' => 599.00, 'sale_price' => null,
                'rating' => 4.9, 'review_count' => 84, 'stock_quantity' => 6, 'weight' => '150g', 'material' => 'Handspun cotton',
                'badges' => ['HANDCRAFTED'], 'image' => '/images/products/gamosa.jpg',
                'gallery' => ['/images/products/gamosa.jpg', '/images/products/gamosa-2.jpg'],
                'short_description' => 'Cotton gamosa woven on a traditional loom, red motif on white ground.',
                'description' => "Handwoven on a traditional pit loom by a weaver in Sualkuchi, this gamosa carries the classic red geometric motif (phul) on an unbleached cotton ground. Used ceremonially and daily across Assam.",
                'specifications' => ['Dimensions' => '96in x 16in', 'Weave' => 'Plain weave, hand-thrown shuttle', 'Wash care' => 'Hand wash cold, first wash separately'],
                'care_instructions' => 'Hand wash in cold water for the first wash to set the dye; line dry in shade.',
                'variants' => [],
            ],
            [
                'id' => 3, 'slug' => 'muga-silk-stole-sualkuchi', 'name' => 'Muga Silk Stole',
                'category' => 'Handloom & Textiles', 'category_slug' => 'handloom-textiles', 'origin' => 'Sualkuchi, Assam',
                'brand' => 'Sualkuchi Weavers Collective', 'sku' => 'KPU-HLM-0003', 'price' => 4200.00, 'sale_price' => 3780.00,
                'rating' => 4.7, 'review_count' => 39, 'stock_quantity' => 11, 'weight' => '120g', 'material' => 'Muga silk',
                'badges' => ['PREMIUM', 'HANDCRAFTED'], 'image' => '/images/products/muga-stole.jpg',
                'gallery' => ['/images/products/muga-stole.jpg', '/images/products/muga-stole-2.jpg'],
                'short_description' => 'Natural golden-sheen muga silk, hand-woven by Sualkuchi artisans.',
                'description' => "Muga silk is unique to Assam and unbleached — the golden sheen is natural to the fibre, not dyed. This stole is hand-woven and gets softer and more lustrous with wear and washing.",
                'specifications' => ['Dimensions' => '80in x 26in', 'Silk type' => 'Muga (Antheraea assamensis)', 'Finish' => 'Undyed, natural gold'],
                'care_instructions' => 'Dry clean recommended. Store folded in a breathable cloth bag.',
                'variants' => [],
            ],
            [
                'id' => 4, 'slug' => 'assam-joha-rice-1kg', 'name' => 'Assam Joha Rice',
                'category' => 'Food & Delicacies', 'category_slug' => 'food-delicacies', 'origin' => 'Nagaon, Assam',
                'brand' => 'Brahmaputra Valley Farmers', 'sku' => 'KPU-FOD-0004', 'price' => 349.00, 'sale_price' => null,
                'rating' => 4.6, 'review_count' => 58, 'stock_quantity' => 73, 'weight' => '1kg', 'material' => null,
                'badges' => ['ORGANIC'], 'image' => '/images/products/joha-rice.jpg',
                'gallery' => ['/images/products/joha-rice.jpg'],
                'short_description' => 'Short-grain, naturally aromatic rice grown in the Brahmaputra floodplain.',
                'description' => "Joha is a short-grain aromatic rice variety native to Assam, traditionally used for festive pulao and payash. This batch is grown without synthetic pesticides on floodplain farms near Nagaon.",
                'specifications' => ['Grain type' => 'Short-grain, aromatic', 'Farming' => 'Pesticide-free', 'Best before' => '12 months from packing'],
                'care_instructions' => 'Store in a cool, dry, airtight container.',
                'variants' => [
                    ['label' => '1kg', 'price' => 349.00, 'sale_price' => null],
                    ['label' => '5kg', 'price' => 1599.00, 'sale_price' => 1449.00],
                ],
            ],
            [
                'id' => 5, 'slug' => 'bell-metal-traditional-bowl', 'name' => 'Bell Metal Traditional Bowl (Kahi)',
                'category' => 'Handicrafts', 'category_slug' => 'handicrafts', 'origin' => 'Sarthebari, Assam',
                'brand' => 'Sarthebari Bell Metal Guild', 'sku' => 'KPU-CFT-0005', 'price' => 1450.00, 'sale_price' => 1250.00,
                'rating' => 4.8, 'review_count' => 22, 'stock_quantity' => 15, 'weight' => '380g', 'material' => 'Bell metal (kahi)',
                'badges' => ['HANDCRAFTED'], 'image' => '/images/products/bell-metal-bowl.jpg',
                'gallery' => ['/images/products/bell-metal-bowl.jpg', '/images/products/bell-metal-bowl-2.jpg'],
                'short_description' => 'Hand-hammered bell metal bowl from the artisan cluster of Sarthebari.',
                'description' => "Hand-hammered by bell-metal (kahi) craftsmen in Sarthebari, Assam's traditional metalwork town. Used for serving during festivals and everyday meals alike. Each bowl carries small variations from hand-forging.",
                'specifications' => ['Diameter' => '5.5in', 'Alloy' => 'Copper-tin bell metal', 'Finish' => 'Hand-hammered, unlacquered'],
                'care_instructions' => 'Hand wash only; a light tarnish is natural to the metal and can be polished with tamarind or a metal polish.',
                'variants' => [],
            ],
            [
                'id' => 6, 'slug' => 'wild-forest-honey-500g', 'name' => 'Assam Wild Forest Honey',
                'category' => 'Food & Delicacies', 'category_slug' => 'food-delicacies', 'origin' => 'Kaziranga fringe villages, Assam',
                'brand' => 'Kaziranga Forest Collective', 'sku' => 'KPU-FOD-0006', 'price' => 549.00, 'sale_price' => null,
                'rating' => 4.7, 'review_count' => 91, 'stock_quantity' => 4, 'weight' => '500g', 'material' => null,
                'badges' => ['ORGANIC', 'LIMITED'], 'image' => '/images/products/honey.jpg',
                'gallery' => ['/images/products/honey.jpg'],
                'short_description' => 'Raw, unprocessed honey foraged from forest-fringe hives near Kaziranga.',
                'description' => "Raw honey foraged from wild and semi-wild hives in villages bordering Kaziranga National Park. Unprocessed and unheated, so texture and colour vary slightly between batches.",
                'specifications' => ['Processing' => 'Raw, unheated, unfiltered', 'Source' => 'Multi-floral forest forage', 'Shelf life' => '24 months'],
                'care_instructions' => 'Store at room temperature; crystallisation is natural and does not affect quality.',
                'variants' => [],
            ],
            [
                'id' => 7, 'slug' => 'bamboo-handcrafted-basket', 'name' => 'Bamboo Handcrafted Storage Basket',
                'category' => 'Handicrafts', 'category_slug' => 'handicrafts', 'origin' => 'Barpeta, Assam',
                'brand' => 'Barpeta Cane & Bamboo Cluster', 'sku' => 'KPU-CFT-0007', 'price' => 899.00, 'sale_price' => 749.00,
                'rating' => 4.5, 'review_count' => 17, 'stock_quantity' => 28, 'weight' => '620g', 'material' => 'Split bamboo cane',
                'badges' => ['HANDCRAFTED', 'NEW'], 'image' => '/images/products/bamboo-basket.jpg',
                'gallery' => ['/images/products/bamboo-basket.jpg'],
                'short_description' => 'Split-cane basket, tightly woven for daily use, from Barpeta craftsmen.',
                'description' => "Tightly woven from split bamboo cane by craftsmen in Barpeta. Sturdy enough for daily storage use, with a natural finish that darkens gracefully over time.",
                'specifications' => ['Dimensions' => '12in x 12in x 10in', 'Material' => 'Split bamboo cane', 'Finish' => 'Natural, unlacquered'],
                'care_instructions' => 'Wipe clean with a dry cloth; keep away from prolonged moisture.',
                'variants' => [],
            ],
            [
                'id' => 8, 'slug' => 'premium-assam-tea-gift-box', 'name' => 'Premium Assam Tea Gift Box',
                'category' => 'Gift Boxes', 'category_slug' => 'gift-boxes', 'origin' => 'Assam, India',
                'brand' => 'KOPOU Curated', 'sku' => 'KPU-GFT-0008', 'price' => 1899.00, 'sale_price' => 1599.00,
                'rating' => 4.9, 'review_count' => 63, 'stock_quantity' => 20, 'weight' => '600g', 'material' => null,
                'badges' => ['BESTSELLER', 'PREMIUM'], 'image' => '/images/products/tea-gift-box.jpg',
                'gallery' => ['/images/products/tea-gift-box.jpg', '/images/products/tea-gift-box-2.jpg'],
                'short_description' => 'Four single-estate teas presented in a hand-finished wooden box.',
                'description' => "Four single-estate teas — orthodox black, CTC, green, and a seasonal specialty — presented in a hand-finished bamboo-inlay wooden box. A popular corporate and festival gift.",
                'specifications' => ['Contents' => '4 x 50g single-estate teas', 'Box' => 'Hand-finished wood with bamboo inlay', 'Includes' => 'Printed tasting notes card'],
                'care_instructions' => 'Store the box away from direct sunlight; keep tea pouches sealed between uses.',
                'variants' => [],
            ],
            [
                'id' => 9, 'slug' => 'eri-silk-shawl', 'name' => 'Eri Silk Shawl',
                'category' => 'Handloom & Textiles', 'category_slug' => 'handloom-textiles', 'origin' => 'Dhemaji, Assam',
                'brand' => 'Dhemaji Eri Weavers', 'sku' => 'KPU-HLM-0009', 'price' => 2600.00, 'sale_price' => 2340.00,
                'rating' => 4.6, 'review_count' => 14, 'stock_quantity' => 9, 'weight' => '340g', 'material' => 'Eri silk',
                'badges' => ['HANDCRAFTED'], 'image' => '/images/products/eri-shawl.jpg',
                'gallery' => ['/images/products/eri-shawl.jpg'],
                'short_description' => 'Warm, matte-textured eri silk shawl, woven by Dhemaji weavers.',
                'description' => "Eri silk is Assam's 'peace silk' — spun from silk staple rather than reeled from live cocoons. This shawl has a warm, matte, slightly nubby texture and is a favourite for Assam's cooler months.",
                'specifications' => ['Dimensions' => '84in x 32in', 'Silk type' => 'Eri (ahimsa/peace silk)', 'Weave' => 'Handspun, hand-woven'],
                'care_instructions' => 'Dry clean or gentle hand wash in cold water; do not wring.',
                'variants' => [],
            ],
            [
                'id' => 10, 'slug' => 'assamese-traditional-pickle', 'name' => 'Assamese Bamboo Shoot Pickle',
                'category' => 'Food & Delicacies', 'category_slug' => 'food-delicacies', 'origin' => 'Jorhat, Assam',
                'brand' => 'Jorhat Home Kitchens', 'sku' => 'KPU-FOD-0010', 'price' => 279.00, 'sale_price' => null,
                'rating' => 4.4, 'review_count' => 33, 'stock_quantity' => 55, 'weight' => '250g', 'material' => null,
                'badges' => [], 'image' => '/images/products/pickle.jpg',
                'gallery' => ['/images/products/pickle.jpg'],
                'short_description' => 'Tangy fermented bamboo shoot pickle, made in small home-kitchen batches.',
                'description' => "A tangy, pungent bamboo shoot pickle (khorisa) made in small batches by home kitchens in Jorhat, following a fermentation method passed down through generations.",
                'specifications' => ['Main ingredient' => 'Fermented bamboo shoot', 'Oil' => 'Cold-pressed mustard oil', 'Shelf life' => '6 months refrigerated after opening'],
                'care_instructions' => 'Refrigerate after opening; use a dry spoon each time.',
                'variants' => [],
            ],
            [
                'id' => 11, 'slug' => 'traditional-bamboo-lamp', 'name' => 'Traditional Bamboo Lamp',
                'category' => 'Handicrafts', 'category_slug' => 'handicrafts', 'origin' => 'Barpeta, Assam',
                'brand' => 'Barpeta Cane & Bamboo Cluster', 'sku' => 'KPU-CFT-0011', 'price' => 1290.00, 'sale_price' => 1090.00,
                'rating' => 4.5, 'review_count' => 9, 'stock_quantity' => 13, 'weight' => '450g', 'material' => 'Bamboo, cotton wiring',
                'badges' => ['HANDCRAFTED', 'NEW'], 'image' => '/images/products/bamboo-lamp.jpg',
                'gallery' => ['/images/products/bamboo-lamp.jpg'],
                'short_description' => 'Hand-built bamboo table lamp with a soft, latticed glow.',
                'description' => "A table lamp hand-built from split bamboo strips over a wire frame, throwing a soft latticed light. Wired to Indian plug standards and BIS-marked components.",
                'specifications' => ['Dimensions' => '10in x 10in x 14in', 'Wiring' => 'BIS-marked, Indian 3-pin plug', 'Bulb' => 'E27, not included'],
                'care_instructions' => 'Dust with a dry brush; keep away from open flame.',
                'variants' => [],
            ],
            [
                'id' => 12, 'slug' => 'assam-green-tea-organic', 'name' => 'Assam Organic Green Tea',
                'category' => 'Assam Tea', 'category_slug' => 'assam-tea', 'origin' => 'Golaghat, Assam',
                'brand' => 'Borbheta Tea Estate', 'sku' => 'KPU-TEA-0012', 'price' => 549.00, 'sale_price' => null,
                'rating' => 4.5, 'review_count' => 47, 'stock_quantity' => 31, 'weight' => '100g', 'material' => null,
                'badges' => ['ORGANIC'], 'image' => '/images/products/tea-green.jpg',
                'gallery' => ['/images/products/tea-green.jpg'],
                'short_description' => 'Lightly oxidised organic green tea from a certified Golaghat estate.',
                'description' => "A lightly oxidised green tea from a certified-organic Golaghat estate. Grassy and mellow, with none of the bitterness that over-brewed green tea can get — steep short, around 2 minutes.",
                'specifications' => ['Certification' => 'India Organic (NPOP)', 'Processing' => 'Pan-fired green', 'Caffeine' => 'Lower than black tea'],
                'care_instructions' => 'Store airtight, away from light and strong odours.',
                'variants' => [
                    ['label' => '100g', 'price' => 549.00, 'sale_price' => null],
                    ['label' => '250g', 'price' => 1199.00, 'sale_price' => null],
                ],
            ],
        ];
    }

    public static function bestsellers(): array
    {
        return array_values(array_filter(self::products(), fn ($p) => in_array('BESTSELLER', $p['badges'], true)));
    }

    public static function byCategory(string $categorySlug): array
    {
        return array_values(array_filter(self::products(), fn ($p) => $p['category_slug'] === $categorySlug));
    }

    public static function findBySlug(string $slug): ?array
    {
        foreach (self::products() as $p) {
            if ($p['slug'] === $slug) {
                return $p;
            }
        }
        return null;
    }

    public static function related(string $slug, int $limit = 4): array
    {
        $current = self::findBySlug($slug);
        if (!$current) {
            return [];
        }
        $pool = array_values(array_filter(
            self::products(),
            fn ($p) => $p['slug'] !== $slug && $p['category_slug'] === $current['category_slug']
        ));
        return array_slice($pool, 0, $limit);
    }

    public static function search(string $query): array
    {
        $query = strtolower(trim($query));
        if ($query === '') {
            return [];
        }
        return array_values(array_filter(
            self::products(),
            fn ($p) => str_contains(strtolower($p['name']), $query)
                || str_contains(strtolower($p['category']), $query)
                || str_contains(strtolower($p['sku']), $query)
        ));
    }
}
