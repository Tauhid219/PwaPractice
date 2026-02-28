<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chapter_id' => \App\Models\Chapter::factory(),
            'question_text' => 'Sample Question ' . \Illuminate\Support\Str::random(10) . '?',
            'answer_text' => 'Sample Answer ' . \Illuminate\Support\Str::random(5),
        ];
    }
}
