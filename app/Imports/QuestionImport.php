<?php

namespace App\Imports;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    private $categoryId;
    private $levelId;

    public function __construct($categoryId, $levelId = null)
    {
        $this->categoryId = $categoryId;
        $this->levelId = $levelId;
    }

    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        // Debugging: If questions are missing, we might want to see what's in $row
        // \Log::info($row);

        // CSV files sometimes have weird encoding in headers,
        // using array_values if row keys are not matching the headers
        $questionText = $row['question_text'] ?? null;
        $answerText = $row['answer_text'] ?? null;
        $acceptableAnswers = $row['acceptable_answers'] ?? null;

        if (! $questionText || ! $answerText) {
            return null; // Skip invalid rows
        }

        $primaryAnswer = trim($answerText);
        $correctAnswers = [$primaryAnswer];

        if ($acceptableAnswers) {
            $alternatives = explode('|', $acceptableAnswers);
            foreach ($alternatives as $alt) {
                $trimmedAlt = trim($alt);
                if ($trimmedAlt !== '' && !in_array($trimmedAlt, $correctAnswers)) {
                    $correctAnswers[] = $trimmedAlt;
                }
            }
        }

        return new Question([
            'category_id' => $this->categoryId,
            'level_id' => $row['level_id'] ?? $this->levelId ?? 1,
            'question_text' => $questionText,
            'option_1' => $row['option_1'] ?? '',
            'option_2' => $row['option_2'] ?? '',
            'option_3' => $row['option_3'] ?? '',
            'option_4' => $row['option_4'] ?? null,
            'answer_text' => $primaryAnswer,
            'correct_answers' => $correctAnswers,
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
