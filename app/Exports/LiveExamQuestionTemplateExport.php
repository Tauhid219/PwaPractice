<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LiveExamQuestionTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['question_text', 'option_1', 'option_2', 'option_3', 'option_4', 'answer_text'];
    }

    public function array(): array
    {
        return [
            ['বাংলাদেশের রাজধানীর নাম কি?', 'ঢাকা', 'চট্টগ্রাম', 'সিলেট', 'খুলনা', 'ঢাকা'],
            ['১ ডজন সমান কতটি?', '১০ টি', '১২ টি', '১৪ টি', '১৬ টি', '১২ টি'],
        ];
    }
}
