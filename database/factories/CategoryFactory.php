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
        $faker = \Faker\Factory::create();
        $name = $faker->unique()->word();
        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'icon' => 'fa-book',
            'description' => $faker->sentence(),
            'order' => $faker->numberBetween(1, 10),
        ];
    }
}
