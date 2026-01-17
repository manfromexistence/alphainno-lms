<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $targetType = $this->faker->randomElement(Announcement::TARGET_TYPES);
        $targetId = null;

        if ($targetType === 'batch') {
            $targetId = Batch::factory();
        } elseif ($targetType === 'course') {
            $targetId = Course::factory();
        }

        return [
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(3, true),
            'target_type' => $targetType,
            'target_id' => $targetId,
            'priority' => $this->faker->randomElement(Announcement::PRIORITIES),
            'starts_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 week', 'now'),
            'expires_at' => $this->faker->optional(0.7)->dateTimeBetween('now', '+1 month'),
            'is_active' => $this->faker->boolean(90),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the announcement targets all users.
     */
    public function forAll(): static
    {
        return $this->state(fn (array $attributes) => [
            'target_type' => 'all',
            'target_id' => null,
        ]);
    }

    /**
     * Indicate that the announcement targets a specific batch.
     */
    public function forBatch(?int $batchId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'target_type' => 'batch',
            'target_id' => $batchId ?? Batch::factory(),
        ]);
    }

    /**
     * Indicate that the announcement targets a specific course.
     */
    public function forCourse(?int $courseId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'target_type' => 'course',
            'target_id' => $courseId ?? Course::factory(),
        ]);
    }

    /**
     * Indicate that the announcement is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    /**
     * Indicate that the announcement is high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }

    /**
     * Indicate that the announcement is normal priority.
     */
    public function normalPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'normal',
        ]);
    }

    /**
     * Indicate that the announcement is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'starts_at' => now()->subDays(1),
            'expires_at' => now()->addDays(7),
        ]);
    }

    /**
     * Indicate that the announcement is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the announcement has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'starts_at' => now()->subDays(14),
            'expires_at' => now()->subDays(1),
        ]);
    }

    /**
     * Indicate that the announcement is scheduled for the future.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'starts_at' => now()->addDays(1),
            'expires_at' => now()->addDays(7),
        ]);
    }
}
