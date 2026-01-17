<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['furniture', 'electronics', 'stationery', 'books', 'equipment', 'supplies'];
        $units = ['pcs', 'box', 'pack', 'set', 'unit', 'kg', 'liter'];
        
        return [
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement($categories),
            'description' => fake()->sentence(),
            'quantity' => fake()->numberBetween(0, 100),
            'unit' => fake()->randomElement($units),
            'unit_price' => fake()->randomFloat(2, 10, 1000),
            'low_stock_threshold' => fake()->numberBetween(5, 20),
            'location' => fake()->optional()->words(2, true),
        ];
    }
}
