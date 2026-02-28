<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

            $category = \App\Models\Category::create($catData);

            // Create 3 chapters per category
            for ($i = 1; $i <= 3; $i++) {
                $chapter = \App\Models\Chapter::create([
                    'category_id' => $category->id,
                    'name' => 'অধ্যায় ' . $i,
                    'slug' => $category->slug . '-chapter-' . $i,
                    'order' => $i,
                ]);

                // Create 5 questions per chapter
                for ($j = 1; $j <= 5; $j++) {
                    \App\Models\Question::create([
                        'chapter_id' => $chapter->id,
                        'question_text' => 'এটি ' . $category->name . ' বিষয়ের একটি নমুনা প্রশ্ন ' . $j . '?',
                        'answer_text' => 'নমুনা উত্তর ' . $j,
                    ]);
                }
            }
        }
    }
}
