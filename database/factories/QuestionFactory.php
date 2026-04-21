<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
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
            'category_id' => Category::factory(),
            'level_id' => Level::factory(),
            'question_text' => 'নমুনা প্রশ্ন '.rand(100, 999).'?',
            'option_1' => 'ক',
            'option_2' => 'খ',
            'option_3' => 'গ',
            'answer_text' => 'ক',
        ];
    }
}
