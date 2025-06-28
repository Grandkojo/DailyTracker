<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true) . ' Department',
        ];
    }

    /**
     * Create an engineering department.
     */
    public function engineering(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Engineering',
        ]);
    }

    /**
     * Create a marketing department.
     */
    public function marketing(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Marketing',
        ]);
    }

    /**
     * Create a human resources department.
     */
    public function humanResources(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Human Resources',
        ]);
    }

    /**
     * Create a finance department.
     */
    public function finance(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Finance',
        ]);
    }
} 