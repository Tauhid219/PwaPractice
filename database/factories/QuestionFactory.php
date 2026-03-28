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
            'category_id' => \App\Models\Category::factory(),
            'level_id' => \App\Models\Level::factory(),
            'question_text' => 'নমুনা প্রশ্ন '.rand(100, 999).'?',
            'option_1' => 'ক',
            'option_2' => 'খ',
            'option_3' => 'গ',
            'answer_text' => 'ক',
        ];
    }
}
