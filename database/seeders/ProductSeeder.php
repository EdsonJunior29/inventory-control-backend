<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Status;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        if (Status::count() === 0) {
            $this->call(StatusSeeder::class);
        }
        
        Product::factory()->count(20)->create([
            'category_id' => function() {
                return Category::inRandomOrder()->first()->id;
            },
            'status_id' => function() {
                return Status::inRandomOrder()->first()->id;
            },
        ])->each(function ($product) {
            // Adiciona mais fornecedores (aleatórios, incluindo o que já foi associado ou não)
            $suppliers = Supplier::inRandomOrder()
                ->take(rand(1, 3))
                ->pluck('id');
            
            $product->suppliers()->sync($suppliers);
        });
    }
}