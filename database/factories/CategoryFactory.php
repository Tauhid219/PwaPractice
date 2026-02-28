<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $name = 'Category ' . \Illuminate\Support\Str::random(5);
        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'icon' => 'fa-book',
            'description' => 'Description for ' . $name,
            'order' => rand(1, 10),
        ];
    }
}
