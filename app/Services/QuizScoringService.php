<?php

namespace App\Services;

use Illuminate\Support\Collection;

class QuizScoringService
{
    /**
     * Compare user answer with correct answer variations in a case-insensitive and space-trimmed manner.
     *
     * @param  string|null  $userAnswer
     * @param  string|array|null  $correctAnswers
     */
    public static function checkAnswer($userAnswer, $correctAnswers): bool
    {
        if ($userAnswer === null || $correctAnswers === null) {
            return false;
        }

        $answersArray = is_array($correctAnswers) ? $correctAnswers : [$correctAnswers];
        $userAnswerTrimmed = trim(mb_strtolower($userAnswer, 'UTF-8'));
        if (class_exists('Normalizer')) {
            $userAnswerTrimmed = \Normalizer::normalize($userAnswerTrimmed, \Normalizer::FORM_C);
        }

        foreach ($answersArray as $correct) {
            $correctTrimmed = trim(mb_strtolower($correct, 'UTF-8'));
            if (class_exists('Normalizer')) {
                $correctTrimmed = \Normalizer::normalize($correctTrimmed, \Normalizer::FORM_C);
            }

            if ($userAnswerTrimmed === $correctTrimmed) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate score from a collection of questions and an array of answers.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|Collection  $questions
     * @param  array  $userAnswers  Array keyed by question ID
     */
    public static function calculateScore($questions, array $userAnswers): int
    {
        $score = 0;
        foreach ($questions as $question) {
            if (array_key_exists($question->id, $userAnswers)) {
                $correctAnswers = $question->correct_answers;
                if (empty($correctAnswers)) {
                    $correctAnswers = [$question->answer_text];
                }
                
                if (self::checkAnswer($userAnswers[$question->id], $correctAnswers)) {
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
