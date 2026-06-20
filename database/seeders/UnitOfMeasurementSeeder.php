<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'Kg', 'Ton', 'Metric Ton (MT)', 'Liter', 'Gallon', 'Piece', 'Unit', 'Set', 'Dozen', 'Meter', 'Other'
        ];

        foreach ($units as $unit) {
            \App\Models\UnitOfMeasurement::firstOrCreate([
                'name' => $unit
            ]);
        }
    }
}
