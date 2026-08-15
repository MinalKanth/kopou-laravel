<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Assam Tea', 'slug' => 'assam-tea', 'image' => 'https://images.unsplash.com/photo-1563822249366-3efb23b8e0c9?q=80&w=900&auto=format&fit=crop', 'description' => 'Dibrugarh — the tea capital of Assam', 'sort_order' => 1],
            ['name' => 'Handloom & Textiles', 'slug' => 'handloom-textiles', 'image' => 'https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?q=80&w=900&auto=format&fit=crop', 'description' => 'Sualkuchi — the silk village on the Brahmaputra', 'sort_order' => 2],
            ['name' => 'Handicrafts', 'slug' => 'handicrafts', 'image' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?q=80&w=900&auto=format&fit=crop', 'description' => 'Sarthebari — bell-metal craftsmanship', 'sort_order' => 3],
            ['name' => 'Food & Delicacies', 'slug' => 'food-delicacies', 'image' => 'https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?q=80&w=900&auto=format&fit=crop', 'description' => "From Assam's home kitchens", 'sort_order' => 4],
            ['name' => 'Gift Boxes', 'slug' => 'gift-boxes', 'image' => 'https://images.unsplash.com/photo-1607344645866-009c320b63e0?q=80&w=900&auto=format&fit=crop', 'description' => 'Curated across every region', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
