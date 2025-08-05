<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProfilesAndUsersSeeder::class,
            SupplierSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class,
            ProductSeeder::class,
        ]);
    }
}