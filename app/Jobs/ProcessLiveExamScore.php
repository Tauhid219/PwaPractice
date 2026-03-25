<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessLiveExamScore implements ShouldQueue
{
    use \Illuminate\Foundation\Bus\Dispatchable, \Illuminate\Queue\InteractsWithQueue, \Illuminate\Foundation\Queue\Queueable, \Illuminate\Queue\SerializesModels;

    protected $exam;
    protected $userId;
    protected $answers;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Models\LiveExam $exam, int $userId, array $answers)
    {
        $this->exam = $exam;
        $this->userId = $userId;
        $this->answers = $answers;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $totalQuestions = $this->exam->questions->count();
        $score = \App\Services\QuizScoringService::calculateScore($this->exam->questions, $this->answers);
        $passed = \App\Services\QuizScoringService::isPassed($score, $totalQuestions);

        \App\Models\LiveExamAttempt::create([
            'user_id' => $this->userId,
            'live_exam_id' => $this->exam->id,
            'score' => $score,
            'passed' => $passed
        ]);
    }
}
