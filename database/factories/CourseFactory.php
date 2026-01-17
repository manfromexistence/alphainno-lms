<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'code' => strtoupper($this->faker->unique()->bothify('CRS-###')),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1000, 50000),
            'duration' => $this->faker->numberBetween(1, 12),
            'duration_unit' => $this->faker->randomElement(['hours', 'days', 'weeks', 'months']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'draft']),
            'image' => $this->faker->optional()->imageUrl(640, 480, 'education'),
            'start_date' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->optional()->dateTimeBetween('now', '+6 months'),
            'max_students' => $this->faker->optional()->numberBetween(20, 100),
            'category' => $this->faker->optional()->word(),
            'class' => $this->faker->optional()->word(),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'prerequisites' => $this->faker->optional()->words(5),
            'objectives' => $this->faker->optional()->sentences(3),
            'syllabus' => $this->faker->optional()->sentences(5),
            'materials_url' => $this->faker->optional()->url(),
        ];
    }

    /**
     * Indicate that the course is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the course is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the course is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
