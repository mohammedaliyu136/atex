<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductPackagingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packagings = [
            'Box', 'Pallet', 'Container', 'Drum', 'Bag', 'Carton', 'Crate', 'Bale', 'Bundle', 'Other'
        ];

        foreach ($packagings as $pkg) {
            \App\Models\ProductPackaging::firstOrCreate([
                'name' => $pkg
            ]);
        }
    }
}
