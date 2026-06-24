<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AtexDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles
        Role::findOrCreate('super-admin');
        Role::findOrCreate('admin');
        Role::findOrCreate('seller');
        Role::findOrCreate('exporter');
        Role::findOrCreate('buyer');
        Role::findOrCreate('logistics');
    }
}
