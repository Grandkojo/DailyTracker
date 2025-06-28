<?php

namespace Database\Factories;

use App\Models\ActivityUpdate;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityUpdate>
 */
class ActivityUpdateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'previous_status' => fake()->randomElement(['pending', 'in_progress', 'done', 'cancelled']),
            'new_status' => fake()->randomElement(['pending', 'in_progress', 'done', 'cancelled']),
            'remark' => fake()->sentence(),
            'user_bio_details' => [
                'id' => 1,
                'name' => fake()->name(),
                'employee_id' => fake()->uuid(),
                'department_id' => null,
                'position' => fake()->jobTitle(),
                'email' => fake()->email(),
            ],
            'update_time' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that this is the initial update (no previous status).
     */
    public function initial(): static
    {
        return $this->state(fn (array $attributes) => [
            'previous_status' => null,
            'new_status' => 'pending',
            'remark' => 'Activity created',
        ]);
    }

    /**
     * Indicate that the activity is being started.
     */
    public function started(): static
    {
        return $this->state(fn (array $attributes) => [
            'previous_status' => 'pending',
            'new_status' => 'in_progress',
            'remark' => 'Started working on the task',
        ]);
    }

    /**
     * Indicate that the activity is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'previous_status' => 'in_progress',
            'new_status' => 'done',
            'remark' => 'Task completed successfully',
        ]);
    }

    /**
     * Indicate that the activity is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'previous_status' => fake()->randomElement(['pending', 'in_progress']),
            'new_status' => 'cancelled',
            'remark' => 'Task cancelled due to requirements change',
        ]);
    }

    /**
     * Indicate that the update has no remark.
     */
    public function withoutRemark(): static
    {
        return $this->state(fn (array $attributes) => [
            'remark' => null,
        ]);
    }
} 