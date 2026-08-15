<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 *
 * Builds believable Assam-origin products by combining real product
 * types with real Assam place names/materials, rather than Faker's
 * generic ->words() output. Used to pad the catalog for load-testing
 * pagination/filtering; the 12 curated products from ProductSeeder
 * remain the ones featured on the homepage and in the previews.
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private const CATALOG = [
        'assam-tea' => [
            'names' => ['CTC Tea', 'Silver Tips White Tea', 'First Flush Black Tea', 'Smoked Tea', 'Masala Chai Blend', 'Estate Reserve Tea'],
            'origins' => ['Jorhat, Assam', 'Golaghat, Assam', 'Tinsukia, Assam', 'Dibrugarh, Assam'],
            'brand' => 'Borbheta Tea Estate',
            'sku_prefix' => 'TEA',
            'price_range' => [299, 1299],
        ],
        'handloom-textiles' => [
            'names' => ['Mekhela Chador Set', 'Pat Silk Dupatta', 'Cotton Gamosa Pair', 'Handwoven Table Runner', 'Eri Silk Cushion Cover'],
            'origins' => ['Sualkuchi, Assam', 'Dhemaji, Assam', 'Nalbari, Assam'],
            'brand' => 'Sualkuchi Weavers Collective',
            'sku_prefix' => 'HLM',
            'price_range' => [599, 6500],
        ],
        'handicrafts' => [
            'names' => ['Bell Metal Plate', 'Cane Fruit Basket', 'Bamboo Wall Hanging', 'Terracotta Diya Set', 'Wood-Carved Mask'],
            'origins' => ['Sarthebari, Assam', 'Barpeta, Assam', 'Majuli, Assam'],
            'brand' => 'Sarthebari Bell Metal Guild',
            'sku_prefix' => 'CFT',
            'price_range' => [349, 2200],
        ],
        'food-delicacies' => [
            'names' => ['Bora Rice', 'Til Pitha', 'Mustard Oil, Cold-Pressed', 'Dried Fish Chutney Mix', 'Black Sesame Ladoo'],
            'origins' => ['Nagaon, Assam', 'Jorhat, Assam', 'Barpeta, Assam'],
            'brand' => 'Jorhat Home Kitchens',
            'sku_prefix' => 'FOD',
            'price_range' => [149, 599],
        ],
        'gift-boxes' => [
            'names' => ['Festival Hamper', 'Corporate Gift Set', 'Handloom Gift Set', 'Tea & Snacks Combo Box'],
            'origins' => ['Assam, India'],
            'brand' => 'KOPOU Curated',
            'sku_prefix' => 'GFT',
            'price_range' => [999, 3499],
        ],
    ];

    public function definition(): array
    {
        $categorySlug = $this->faker->randomElement(array_keys(self::CATALOG));
        $spec = self::CATALOG[$categorySlug];
        $name = $this->faker->randomElement($spec['names']);
        $price = $this->faker->randomFloat(2, $spec['price_range'][0], $spec['price_range'][1]);
        $onSale = $this->faker->boolean(35);

        return [
            'category_id' => Category::where('slug', $categorySlug)->value('id'),
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(100, 999),
            'sku' => 'KPU-'.$spec['sku_prefix'].'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'brand' => $spec['brand'],
            'origin' => $this->faker->randomElement($spec['origins']),
            'price' => $price,
            'sale_price' => $onSale ? round($price * $this->faker->randomFloat(2, 0.75, 0.92), 2) : null,
            'weight' => $this->faker->randomElement(['100g', '250g', '500g', '1kg', null]),
            'material' => null,
            'short_description' => "Authentic {$name} sourced from {$spec['brand']}.",
            'description' => "A genuine {$name}, part of KOPOU's curated Assam catalog. Full sourcing and artisan details are added when this listing is reviewed by the catalog team.",
            'care_instructions' => 'Store in a cool, dry place away from direct sunlight.',
            'specifications' => ['Origin' => $this->faker->randomElement($spec['origins'])],
            'badges' => $this->faker->boolean(20) ? ['NEW'] : [],
            'rating' => $this->faker->randomFloat(1, 3.8, 5.0),
            'review_count' => $this->faker->numberBetween(2, 60),
            'is_featured' => false,
            'is_bestseller' => false,
            'status' => 'active',
        ];
    }
}
