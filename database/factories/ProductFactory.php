<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'brand' => $this->faker->company,
            'category_id' => Category::factory(),
            'description' => substr($this->faker->paragraph, 0, 255),
            'quantity_in_stock' => $this->faker->numberBetween(0, 500),
            'serial_number' => $this->faker->unique()->bothify('SN-#####-???'),
            'date_of_acquisition' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'status_id' => Status::factory(),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null
        ];
    }
}