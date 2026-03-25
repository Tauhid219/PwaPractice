<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'কুরআনুল কারীম', 'slug' => 'quranul-kareem', 'icon' => 'fa-book-open', 'file' => 'QuranulKareemQuestions.php'],
            ['name' => 'রাসুল (সা), হাদীস এবং সাহাবায়ে কেরাম', 'slug' => 'rasul-hadith-sahaba', 'icon' => 'fa-book-open', 'file' => 'RasulHadithSahabaQuestions.php'],
            ['name' => 'বিবিধ ইসলামী জ্ঞান', 'slug' => 'islamic-knowledge', 'icon' => 'fa-mosque', 'file' => 'IslamicKnowledgeQuestions.php'],
            ['name' => 'ইতিহাস ঐতিহ্য', 'slug' => 'history-heritage', 'icon' => 'fa-landmark', 'file' => 'HistoryHeritageQuestions.php'],
            ['name' => 'শিক্ষা, সাহিত্য, সংস্কৃতি', 'slug' => 'education-literature-culture', 'icon' => 'fa-graduation-cap', 'file' => 'EducationLiteratureCultureQuestions.php'],
            ['name' => 'খেলাধুলা', 'slug' => 'sports', 'icon' => 'fa-futbol', 'file' => 'SportsQuestions.php'],
            ['name' => 'বিজ্ঞান, প্রযুক্তি ও ভূগোল', 'slug' => 'science-tech-geography', 'icon' => 'fa-microscope', 'file' => 'ScienceTechGeographyQuestions.php'],
            ['name' => 'বিচিত্র বিশ্ব', 'slug' => 'astonishing-world', 'icon' => 'fa-globe', 'file' => 'AstonishingWorldQuestions.php'],
            ['name' => 'আমার দেশ বাংলাদেশ', 'slug' => 'bangladesh', 'icon' => 'fa-flag', 'file' => 'BangladeshQuestions.php'],
        ];

        foreach ($categories as $index => $catData) {
            $questionFile = $catData['file'];
            unset($catData['file']);

            $catData['order'] = $index + 1;
            $catData['description'] = $catData['name'].' বিষয়ক প্রশ্ন ও উত্তর।';

            $category = Category::create($catData);

            // Load questions from separate file
            $questions = require __DIR__.'/questions/'.$questionFile;

            foreach ($questions as $qData) {
                $options = [$qData[2], $qData[3], $qData[4]];
                shuffle($options);

                Question::create([
                    'category_id' => $category->id,
                    'level_id' => $qData[0],
                    'question_text' => $qData[1],
                    'option_1' => $options[0],
                    'option_2' => $options[1],
                    'option_3' => $options[2],
                    'answer_text' => $qData[5],
                ]);
            }
        }
    }
}
