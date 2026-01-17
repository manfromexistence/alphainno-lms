<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Batch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true) . ' Batch',
            'code' => strtoupper($this->faker->unique()->bothify('BAT-###')),
            'course_id' => Course::factory(),
            'schedule' => $this->faker->optional()->words(5, true),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'max_students' => $this->faker->numberBetween(20, 50),
            'status' => $this->faker->randomElement(['active', 'inactive', 'completed']),
            'room' => $this->faker->optional()->bothify('Room-##'),
            'teacher_id' => null,
        ];
    }

    /**
     * Indicate that the batch is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the batch is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the batch is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }
}
