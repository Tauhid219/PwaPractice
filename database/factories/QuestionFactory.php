<?php

namespace Database\Factories;

use App\Models\Chapter;
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
            'chapter_id' => Chapter::factory(),
            'question_text' => 'নমুনা প্রশ্ন '.rand(100, 999).'?',
            'answer_text' => 'Sample Answer '.\Illuminate\Support\Str::random(5),
        ];
    }
}
