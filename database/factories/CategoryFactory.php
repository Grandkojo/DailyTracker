<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
        ];
    }

    /**
     * Create a development category.
     */
    public function development(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Development',
        ]);
    }

    /**
     * Create a testing category.
     */
    public function testing(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Testing',
        ]);
    }

    /**
     * Create a maintenance category.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Maintenance',
        ]);
    }
} 