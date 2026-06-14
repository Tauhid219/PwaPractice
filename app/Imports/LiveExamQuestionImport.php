<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\LiveExam;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LiveExamQuestionImport implements ToModel, WithHeadingRow
{
    private LiveExam $liveExam;

    public function __construct(LiveExam $liveExam)
    {
        $this->liveExam = $liveExam;
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $questionText = $row['question_text'] ?? null;
        $answerText = $row['answer_text'] ?? null;

        if (!$questionText || !$answerText) {
            return null; // Skip invalid rows
        }

        $question = Question::create([
            'category_id' => null,
            'level_id' => null,
            'question_text' => $questionText,
            'option_1' => $row['option_1'] ?? '',
            'option_2' => $row['option_2'] ?? '',
            'option_3' => $row['option_3'] ?? '',
            'option_4' => $row['option_4'] ?? '',
            'answer_text' => trim($answerText),
            'correct_answers' => [trim($answerText)],
        ]);

        // Link to the Live Exam
        $this->liveExam->questions()->attach($question->id);

        return null;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
