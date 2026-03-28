<?php

namespace Tests\Unit;

use App\Services\QuizScoringService;
use PHPUnit\Framework\TestCase;

class QuizScoringServiceTest extends TestCase
{
    /**
     * Test checkAnswer identifies correct/incorrect answers correctly.
     */
    public function test_checkAnswer_identifies_correct_incorrect_answers()
    {
        // Identical
        $this->assertTrue(QuizScoringService::checkAnswer('Apple', 'Apple'));
        // Case-insensitive
        $this->assertTrue(QuizScoringService::checkAnswer('apple', 'Apple'));
        // Trimming
        $this->assertTrue(QuizScoringService::checkAnswer(' apple ', 'Apple'));
        // Incorrect
        $this->assertFalse(QuizScoringService::checkAnswer('Orange', 'Apple'));
        // Null values
        $this->assertFalse(QuizScoringService::checkAnswer(null, 'Apple'));
        $this->assertFalse(QuizScoringService::checkAnswer('Apple', null));
        $this->assertFalse(QuizScoringService::checkAnswer(null, null));
    }

    /**
     * Test calculateScore returns a correct matching count.
     */
    public function test_calculateScore_returns_correct_matching_count()
    {
        // Mock collection of questions
        $questions = collect([
            (object) ['id' => 1, 'answer_text' => 'A'],
            (object) ['id' => 2, 'answer_text' => 'B'],
            (object) ['id' => 3, 'answer_text' => 'C'],
        ]);

        $userAnswers = [
            1 => 'A', // Correct
            2 => 'X', // Incorrect
            3 => 'c ', // Correct (case + space)
        ];

        $score = QuizScoringService::calculateScore($questions, $userAnswers);

        $this->assertEquals(2, $score);
    }

    /**
     * Test isPassed handles percentage logic correctly.
     */
    public function test_isPassed_handles_percentage_logic()
    {
        // By default, it's 80%? But since it's a unit test using PHPUnit\Framework\TestCase,
        // it doesn't load the Laravel context (and config) by default.
        // QuizScoringService handles config() call inside isPassed.
        // To be safe, we can mock the percentage, but let's see how QuizScoringService handles it.
        // Actually, for a pure unit test, if it calls config(), it might fail if not using Tests\TestCase.

        // If I use Tests\TestCase, I get Laravel environment.
        // Let's use 80 as a fixed passing percentage for testing.
        
        $this->assertTrue(QuizScoringService::isPassed(8, 10, 80)); // Exactly 80%
        $this->assertTrue(QuizScoringService::isPassed(9, 10, 80)); // 90%
        $this->assertFalse(QuizScoringService::isPassed(7, 10, 80)); // 70%

        // Edge Cases
        $this->assertFalse(QuizScoringService::isPassed(1, 0, 80)); // Division by zero check
    }
}
