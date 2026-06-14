<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['question_text', 'answer_text', 'acceptable_answers'];
    }

    public function array(): array
    {
        return [
            ['আমাদের জাতীয় ফলের নাম কি?', 'কাঁঠাল', 'কাঠাল|kathal'],
            ['বাংলাদেশের রাজধানীর নাম কি?', 'ঢাকা', 'dhaka'],
        ];
    }
}
