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

    protected $exam;

    protected $userId;

    protected $answers;

    protected $tabSwitches;

    /**
     * Create a new job instance.
     */
    public function __construct(LiveExam $exam, int $userId, array $answers, int $tabSwitches = 0)
    {
        $this->exam = $exam;
        $this->userId = $userId;
        $this->answers = $answers;
        $this->tabSwitches = $tabSwitches;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $questions = Cache::remember("exam_questions_{$this->exam->id}", 3600, function () {
            return $this->exam->questions;
        });

        $totalQuestions = $questions->count();
        $score = QuizScoringService::calculateScore($questions, $this->answers);
        $passed = QuizScoringService::isPassed($score, $totalQuestions);

        LiveExamAttempt::create([
            'user_id' => $this->userId,
            'live_exam_id' => $this->exam->id,
            'score' => $score,
            'passed' => $passed,
            'tab_switches' => $this->tabSwitches,
        ]);
    }
}
