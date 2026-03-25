<?php

namespace Database\Factories;

use App\Models\Category;
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
        $name = 'Chapter '.\Illuminate\Support\Str::random(5);

        return [
            'category_id' => Category::factory(),
            'name' => 'অধ্যায় '.rand(1, 10),
            'slug' => \Illuminate\Support\Str::slug($name),
            'order' => rand(1, 20),
        ];
    }
}
