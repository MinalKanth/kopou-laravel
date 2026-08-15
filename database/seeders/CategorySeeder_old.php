<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Assam Tea', 'slug' => 'assam-tea', 'image' => '/images/categories/tea.jpg', 'sort_order' => 1],
            ['name' => 'Handloom & Textiles', 'slug' => 'handloom-textiles', 'image' => '/images/categories/handloom.jpg', 'sort_order' => 2],
            ['name' => 'Handicrafts', 'slug' => 'handicrafts', 'image' => '/images/categories/handicrafts.jpg', 'sort_order' => 3],
            ['name' => 'Food & Delicacies', 'slug' => 'food-delicacies', 'image' => '/images/categories/food.jpg', 'sort_order' => 4],
            ['name' => 'Gift Boxes', 'slug' => 'gift-boxes', 'image' => '/images/categories/gifts.jpg', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
