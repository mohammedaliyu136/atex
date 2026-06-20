<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics & Technology',
            'Fashion & Apparel',
            'Home & Furniture',
            'Beauty & Personal Care',
            'Health & Wellness',
            'Sports & Outdoors',
            'Toys, Kids & Baby',
            'Automotive & Industrial',
            'Food & Beverages',
            'Office & Stationery',
            'General Merchandise',
            'Other'
        ];

        foreach ($categories as $cat) {
            \App\Models\BusinessCategory::firstOrCreate([
                'name' => $cat
            ]);
        }
    }
}
