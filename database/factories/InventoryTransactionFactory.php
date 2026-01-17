<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryTransaction>
 */
class InventoryTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['purchase', 'usage', 'adjustment'];
        $type = fake()->randomElement($types);
        $quantity = fake()->numberBetween(1, 50);
        $unitPrice = fake()->randomFloat(2, 10, 500);
        
        return [
            'inventory_item_id' => InventoryItem::factory(),
            'type' => $type,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_amount' => $quantity * $unitPrice,
            'supplier' => $type === 'purchase' ? fake()->company() : null,
            'purpose' => $type === 'usage' ? fake()->sentence(3) : null,
            'transaction_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the transaction is a purchase.
     */
    public function purchase(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'purchase',
            'supplier' => fake()->company(),
            'purpose' => null,
        ]);
    }

    /**
     * Indicate that the transaction is a usage.
     */
    public function usage(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'usage',
            'supplier' => null,
            'purpose' => fake()->sentence(3),
        ]);
    }

    /**
     * Indicate that the transaction is an adjustment.
     */
    public function adjustment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'adjustment',
            'supplier' => null,
            'purpose' => fake()->sentence(3),
        ]);
    }
}
