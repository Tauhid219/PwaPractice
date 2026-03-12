<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Question;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'কুরআনুল কারীম', 'slug' => 'quranul-kareem', 'icon' => 'fa-book-open'],
            ['name' => 'রাসুল (সা), হাদীস এবং সাহাবায়ে কেরাম', 'slug' => 'rasul-hadith-sahaba', 'icon' => 'fa-book-open'],
            ['name' => 'বিবিধ ইসলামী জ্ঞান', 'slug' => 'islamic-knowledge', 'icon' => 'fa-mosque'],
            ['name' => 'ইতিহাস ঐতিহ্য', 'slug' => 'history-heritage', 'icon' => 'fa-landmark'],
            ['name' => 'শিক্ষা, সাহিত্য, সংস্কৃতি', 'slug' => 'education-literature-culture', 'icon' => 'fa-graduation-cap'],
            ['name' => 'খেলাধুলা', 'slug' => 'sports', 'icon' => 'fa-futbol'],
            ['name' => 'বিজ্ঞান, প্রযুক্তি ও ভূগোল', 'slug' => 'science-tech-geography', 'icon' => 'fa-microscope'],
            ['name' => 'বিচিত্র বিশ্ব', 'slug' => 'astonishing-world', 'icon' => 'fa-globe'],
            ['name' => 'আমার দেশ বাংলাদেশ', 'slug' => 'bangladesh', 'icon' => 'fa-flag'],
        ];

        // Format: [level_id, 'Question', 'Opt 1', 'Opt 2', 'Opt 3', 'Correct Answer']
        $dummyQuestions = [
            [1, 'আমাদের জাতীয় মাছের নাম কি?', 'ইলিশ', 'রুই', 'কাতল', 'ইলিশ'],
            [1, 'সূর্য কোন দিকে ওঠে?', 'পূর্ব', 'পশ্চিম', 'উত্তর', 'পূর্ব'],
            [1, 'মানুষের কয়টি হাত থাকে?', '২টি', '৩টি', '৪টি', '২টি'],
            [1, 'পানির অপর নাম কি?', 'জীবন', 'মরণ', 'খাবার', 'জীবন'],
            [1, 'পাখি কোথায় ওড়ে?', 'আকাশে', 'মাটিতে', 'জলে', 'আকাশে'],
            
            [2, 'বলো তো ২ আর ২ কত হয়?', '৪', '৫', '৬', '৪'],
            [2, 'আমরা কোন ভাষায় কথা বলি?', 'বাংলা', 'ইংরেজি', 'আরবি', 'বাংলা'],
            [2, 'অ্যাম্বুলেন্স কেন লাগে?', 'রোগী নিতে', 'বিয়ে বাড়িতে', 'বাজারে যেতে', 'রোগী নিতে'],
            [2, 'বই পড়লে কি হয়?', 'জ্ঞান বাড়ে', 'ক্ষুধা লাগে', 'ঘুম আসে', 'জ্ঞান বাড়ে'],
            [2, 'আকাশের রং কি?', 'নীল', 'লাল', 'সবুজ', 'নীল'],
            
            [3, 'আমাদের জাতীয় পতাকার রং কি কি?', 'লাল ও সবুজ', 'নীল ও সাদা', 'হলুদ ও লাল', 'লাল ও সবুজ'],
            [3, 'গরু আমাদের কি দেয়?', 'দুধ', 'ডিম', 'মধু', 'দুধ'],
            [3, 'ফল খেলে কি হয়?', 'স্বাস্থ্য ভালো থাকে', 'শরীল খারাপ হয়', 'দাঁত পড়ে যায়', 'স্বাস্থ্য ভালো থাকে'],
            [3, 'স্কুলে আমরা কেন যাই?', 'লেখাপড়া করতে', 'খেলতে', 'ঘুমাতে', 'লেখাপড়া করতে'],
            [3, 'একটি ত্রিভুজের কয়টি কোণ থাকে?', '৩টি', '৪টি', '৫টি', '৩টি'],
        ];

        foreach ($categories as $index => $catData) {
            $catData['order'] = $index + 1;
            $catData['description'] = $catData['name'] . ' বিষয়ক প্রশ্ন ও উত্তর।';

            $category = Category::create($catData);

            foreach ($dummyQuestions as $qData) {
                $options = [$qData[2], $qData[3], $qData[4]];
                shuffle($options);

                Question::create([
                    'category_id'  => $category->id,
                    'level_id'     => $qData[0],
                    'question_text'=> $qData[1],
                    'option_1'     => $options[0],
                    'option_2'     => $options[1],
                    'option_3'     => $options[2],
                    'answer_text'  => $qData[5],
                ]);
            }
        }
    }
}
