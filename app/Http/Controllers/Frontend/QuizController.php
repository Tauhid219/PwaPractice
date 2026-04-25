<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitQuizRequest;
use App\Models\Category;
use App\Models\Level;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use App\Services\QuizScoringService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class QuizController extends Controller
{
    /**
     * Start the quiz for a specific level.
     */
    public function start($slug, Level $level)
    {
        $category = Cache::rememberForever('category_full_'.$slug, function () use ($slug) {
            return Category::where('slug', $slug)->firstOrFail();
        });

        // Access is handled by CheckLevelAccess middleware
        // Fetch questions for this level and category
        $questions = $level->questions()
            ->where('category_id', $category->id)
            ->inRandomOrder()
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions found for this level.');
        }

        return view('frontend.quiz.taking', compact('category', 'level', 'questions'));
    }

    /**
     * Submit quiz answers and calculate score.
     */
    public function submit(SubmitQuizRequest $request, $slug, Level $level)
    {
        $category = Cache::rememberForever('category_full_'.$slug, function () use ($slug) {
            return Category::where('slug', $slug)->firstOrFail();
        });

        $questions = $level->questions()->where('category_id', $category->id)->get();
        $totalQuestions = $questions->count();

        $score = QuizScoringService::calculateScore($questions, $request->answers);
        $passed = QuizScoringService::isPassed($score, $totalQuestions);

        // Record Attempt
        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'category_id' => $category->id,
            'level_id' => $level->id,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'passed' => $passed,
        ]);

        // If passed, unlock or complete
        if ($passed) {
            $user = Auth::user();

            // Streak logic
            $user->updateStreak();

            UserProgress::updateOrCreate(
                ['user_id' => $user->id, 'category_id' => $category->id, 'level_id' => $level->id],
                ['status' => 'completed']
            );

            // Unlock next level logic for this specific category
            $nextLevel = Level::where('category_id', $category->id)
                ->where('order', '>', $level->order)
                ->orderBy('order', 'asc')
                ->first();

            if ($nextLevel) {
                UserProgress::firstOrCreate(
                    ['user_id' => $user->id, 'category_id' => $category->id, 'level_id' => $nextLevel->id],
                    ['status' => 'active']
                );
            }
        }

        return redirect()->route('quiz.result', $attempt->id);
    }

    /**
     * Show quiz result.
     */
    public function result(QuizAttempt $attempt)
    {
        // Ensure the user owns this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        // Load relationships to avoid N+1 in view
        $attempt->load(['level', 'category']);
        $level = $attempt->level;
        $category = $attempt->category;
        $totalQuestions = $attempt->total_questions;

        return view('frontend.quiz.result', compact('attempt', 'level', 'totalQuestions', 'category'));
    }
}
