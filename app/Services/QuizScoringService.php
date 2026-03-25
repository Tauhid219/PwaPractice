<?php

namespace App\Services;

class QuizScoringService
{
    /**
     * Compare user answer with correct answer in a case-insensitive and space-trimmed manner.
     *
     * @param  string|null  $userAnswer
     * @param  string|null  $correctAnswer
     */
    public static function checkAnswer($userAnswer, $correctAnswer): bool
    {
        if ($userAnswer === null || $correctAnswer === null) {
            return false;
        }

        return trim(strtolower($userAnswer)) === trim(strtolower($correctAnswer));
    }

    /**
     * Calculate score from a collection of questions and an array of answers.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection  $questions
     * @param  array  $userAnswers  Array keyed by question ID
     */
    public static function calculateScore($questions, array $userAnswers): int
    {
        $score = 0;
        foreach ($questions as $question) {
            if (array_key_exists($question->id, $userAnswers)) {
                if (self::checkAnswer($userAnswers[$question->id], $question->answer_text)) {
                    $score++;
                }
            }
        }

        return $score;
    }

    /**
     * Determine if a calculated score passes the required percentage threshold.
     */
    public static function isPassed(int $score, int $totalQuestions, ?int $passingPercentage = null): bool
    {
        if ($totalQuestions === 0) {
            return false;
        }
        $passingPercentage = $passingPercentage ?? config('quiz.passing_percentage', 80);
        $percentage = ($score / $totalQuestions) * 100;

        return $percentage >= $passingPercentage;
    }
}
