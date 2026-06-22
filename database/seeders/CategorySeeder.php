<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Agriculture & Food' => ['Grains & Cereals', 'Fruits & Vegetables', 'Nuts & Seeds', 'Spices & Herbs', 'Livestock & Poultry'],
            'Minerals & Solid Minerals' => ['Precious Stones', 'Industrial Minerals', 'Base Metals'],
            'Textiles & Garments' => ['Fabrics', 'Traditional Attire', 'Footwear'],
            'Arts & Crafts' => ['Wood Carvings', 'Pottery & Ceramics', 'Beadwork', 'Leather Goods'],
            'Manufactured Goods' => ['Processed Foods', 'Cosmetics & Personal Care', 'Building Materials'],
            'Energy & Petroleum' => ['Crude Oil', 'Natural Gas', 'Refined Products'],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = Category::firstOrCreate([
                'slug' => Str::slug($parent),
            ], [
                'name' => $parent,
                'status' => true,
            ]);

            foreach ($children as $child) {
                Category::firstOrCreate([
                    'slug' => Str::slug($child),
                ], [
                    'name' => $child,
                    'parent_id' => $parentCategory->id,
                    'status' => true,
                ]);
            }
        }
    }
}
