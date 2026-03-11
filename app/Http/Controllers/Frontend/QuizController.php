<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Start the quiz for a specific level.
     */
    public function start($slug, Level $level)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
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
    public function submit(Request $request, $slug, Level $level)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        $questions = $level->questions()->where('category_id', $category->id)->get();
        $score = 0;
        $totalQuestions = $questions->count();

        foreach ($questions as $question) {
            if (isset($request->answers[$question->id]) && 
                trim(strtolower($request->answers[$question->id])) == trim(strtolower($question->answer_text))) {
                $score++;
            }
        }

        // Pass threshold (e.g., 80% or 100%)
        // For simplicity, let's say 80% is required
        $percentage = ($score / $totalQuestions) * 100;
        $passed = $percentage >= 80;

        // Record Attempt
        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'level_id' => $level->id,
            'score' => $score,
            'passed' => $passed
        ]);

        // If passed, unlock or complete
        if ($passed) {
             UserProgress::updateOrCreate(
                ['user_id' => Auth::id(), 'category_id' => $category->id, 'level_id' => $level->id],
                ['status' => 'completed']
            );

            // Unlock next level logic for this specific category
            $nextLevel = Level::where('id', '>', $level->id)->orderBy('id', 'asc')->first();
            if ($nextLevel) {
                 UserProgress::firstOrCreate(
                    ['user_id' => Auth::id(), 'category_id' => $category->id, 'level_id' => $nextLevel->id],
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

        $level = $attempt->level;
        $category = $level->questions()->first()->category ?? null; // Assuming all level questions belong to same category
        $totalQuestions = $level->questions()->count();

        return view('frontend.quiz.result', compact('attempt', 'level', 'totalQuestions', 'category'));
    }
}
