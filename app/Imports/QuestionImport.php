<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    private $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
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
            'category_id'   => $this->categoryId,
            'question_text' => $row['question_text'],
            'answer_text'   => $row['answer_text'],
        ]);
    }
}
