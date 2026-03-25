<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
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
        if (! isset($row['question_text']) || ! isset($row['answer_text'])) {
            return null; // Skip invalid rows
        }

        return new Question([
            'category_id' => $this->categoryId,
            'level_id' => $row['level_id'] ?? 1, // Default to level 1 if not provided
            'question_text' => $row['question_text'],
            'option_1' => $row['option_1'] ?? '',
            'option_2' => $row['option_2'] ?? '',
            'option_3' => $row['option_3'] ?? '',
            'answer_text' => $row['answer_text'],
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
