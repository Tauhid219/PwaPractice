<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Question;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'বাংলা', 'slug' => 'bangla', 'icon' => 'fa-language'],
            ['name' => 'ইংরেজি', 'slug' => 'english', 'icon' => 'fa-font'],
            ['name' => 'গণিত', 'slug' => 'math', 'icon' => 'fa-calculator'],
            ['name' => 'বিজ্ঞান', 'slug' => 'science', 'icon' => 'fa-flask'],
            ['name' => 'সাধারণ জ্ঞান', 'slug' => 'general-knowledge', 'icon' => 'fa-globe'],
        ];

        foreach ($categories as $index => $catData) {
            $catData['order'] = $index + 1;
            $catData['description'] = $catData['name'] . ' বিষয়ক প্রশ্ন ও উত্তর।';

            $category = Category::create($catData);

            // Create 15 dummy questions per category (5 per level: levels 1, 2, 3)
            for ($i = 1; $i <= 15; $i++) {
                $levelId = 1;
                if ($i > 5 && $i <= 10) {
                    $levelId = 2;
                } elseif ($i > 10) {
                    $levelId = 3;
                }

                Question::create([
                    'category_id' => $category->id,
                    'level_id' => $levelId,
                    'question_text' => 'এটি ' . $category->name . ' লেভেল ' . $levelId . ' এর একটি নমুনা প্রশ্ন ' . $i . '?',
                    'answer_text' => 'নমুনা উত্তর ' . $i,
                ]);
            }
        }
    }
}
