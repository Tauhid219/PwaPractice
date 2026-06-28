<?php

namespace App\Jobs;

use App\Models\LiveExam;
use App\Models\LiveExamAttempt;
use App\Services\QuizScoringService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessLiveExamScore
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attempt;

    protected $answers;

    /**
     * Create a new job instance.
     */
    public function __construct(LiveExamAttempt $attempt, array $answers)
    {
        $this->attempt = $attempt;
        $this->answers = $answers;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $exam = $this->attempt->exam;
        
        $questions = Cache::remember("exam_questions_{$exam->id}", 3600, function () use ($exam) {
            return $exam->questions;
        });

        $totalQuestions = $questions->count();
        $score = QuizScoringService::calculateScore($questions, $this->answers);
        $passed = QuizScoringService::isPassed($score, $totalQuestions);

        $this->attempt->update([
            'score' => $score,
            'passed' => $passed,
            'status' => 'completed',
        ]);
    }
}
