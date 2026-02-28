<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => ucwords($name),
            'slug' => str($name)->slug(),
            'order' => fake()->numberBetween(1, 20),
        ];
    }
}
