<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(Expense::CATEGORIES),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'description' => $this->faker->sentence(),
            'expense_date' => $this->faker->date(),
            'receipt_number' => $this->faker->optional()->numerify('EXP-####-####'),
            'notes' => $this->faker->optional()->paragraph(),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the expense is for rent.
     */
    public function rent(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'rent',
        ]);
    }

    /**
     * Indicate that the expense is for salary.
     */
    public function salary(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'salary',
        ]);
    }

    /**
     * Indicate that the expense is for bills.
     */
    public function bills(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'bills',
        ]);
    }
}
