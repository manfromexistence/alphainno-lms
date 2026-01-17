<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\Student;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Income::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(Income::CATEGORIES),
            'amount' => $this->faker->randomFloat(2, 100, 50000),
            'description' => $this->faker->sentence(),
            'income_date' => $this->faker->date(),
            'student_id' => null,
            'payment_id' => null,
            'reference' => $this->faker->optional()->numerify('INC-####-####'),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the income is from admission.
     */
    public function admission(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'admission',
        ]);
    }

    /**
     * Indicate that the income is from tuition.
     */
    public function tuition(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'tuition',
        ]);
    }

    /**
     * Indicate that the income is from materials.
     */
    public function materials(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'materials',
        ]);
    }

    /**
     * Indicate that the income is linked to a student.
     */
    public function forStudent(?Student $student = null): static
    {
        return $this->state(fn (array $attributes) => [
            'student_id' => $student?->id,
        ]);
    }

    /**
     * Indicate that the income is linked to a payment.
     */
    public function fromPayment(?Payment $payment = null): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_id' => $payment?->id,
            'student_id' => $payment?->student_id,
        ]);
    }
}
