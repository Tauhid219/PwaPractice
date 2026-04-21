<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Chapter>
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
        $name = 'Chapter '.Str::random(5);

        return [
            'category_id' => Category::factory(),
            'name' => 'অধ্যায় '.rand(1, 10),
            'slug' => Str::slug($name),
            'order' => rand(1, 20),
        ];
    }
}
