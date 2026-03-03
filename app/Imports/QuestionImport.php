<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    private $chapterId;

    public function __construct($chapterId)
    {
        $this->chapterId = $chapterId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row['question_text']) || !isset($row['answer_text'])) {
            return null; // Skip invalid rows
        }

        return new Question([
            'chapter_id'    => $this->chapterId,
            'question_text' => $row['question_text'],
            'answer_text'   => $row['answer_text'],
        ]);
    }
}
