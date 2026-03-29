<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    private $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Debugging: If questions are missing, we might want to see what's in $row
        // \Log::info($row); 

        // CSV files sometimes have weird encoding in headers, 
        // using array_values if row keys are not matching the headers
        $questionText = $row['question_text'] ?? null;
        $answerText = $row['answer_text'] ?? null;

        if (!$questionText || !$answerText) {
            return null; // Skip invalid rows
        }

        return new Question([
            'category_id' => $this->categoryId,
            'level_id' => $row['level_id'] ?? 1, 
            'question_text' => $questionText,
            'option_1' => $row['option_1'] ?? '',
            'option_2' => $row['option_2'] ?? '',
            'option_3' => $row['option_3'] ?? '',
            'answer_text' => $answerText,
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
