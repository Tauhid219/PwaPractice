<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use App\Models\LiveExamAttempt;
use App\Services\QuizScoringService;
use App\Models\LiveExam;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLiveExamScore
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exam;
    protected $userId;
    protected $answers;

    /**
     * Create a new job instance.
     */
    public function __construct(LiveExam $exam, int $userId, array $answers)
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
        $score = QuizScoringService::calculateScore($this->exam->questions, $this->answers);
        $passed = QuizScoringService::isPassed($score, $totalQuestions);

        LiveExamAttempt::create([
            'user_id' => $this->userId,
            'live_exam_id' => $this->exam->id,
            'score' => $score,
            'passed' => $passed
        ]);
    }
}
