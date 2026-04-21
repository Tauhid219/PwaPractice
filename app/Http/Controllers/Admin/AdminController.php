<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Level;
use App\Models\LiveExam;
use App\Models\LiveExamAttempt;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with analytics and charts.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Basic Counts
        $categoryCount = Category::count();
        $questionCount = Question::count();
        $userCount = User::count();
        $liveExamCount = LiveExam::count();

        // 2. Performance Stats
        $quizAttemptsCount = QuizAttempt::count();
        $liveExamAttemptsCount = LiveExamAttempt::count();
        $totalAttempts = $quizAttemptsCount + $liveExamAttemptsCount;

        $quizPassedCount = QuizAttempt::where('passed', true)->count();
        $liveExamPassedCount = LiveExamAttempt::where('passed', true)->count();
        $totalPassed = $quizPassedCount + $liveExamPassedCount;

        $passRate = $totalAttempts > 0 ? round(($totalPassed / $totalAttempts) * 100, 2) : 0;

        // 3. Last 7 Days Activity (Trend)
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        
        $quizAttempts = QuizAttempt::where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $examAttempts = LiveExamAttempt::where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $days = [];
        $quizData = [];
        $examData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $days[] = Carbon::now()->subDays($i)->format('D');
            $quizData[] = $quizAttempts->get($date, 0);
            $examData[] = $examAttempts->get($date, 0);
        }

        // 4. Category-wise Question Distribution
        $categories = Category::withCount('questions')->get();
        $categoryNames = $categories->pluck('name');
        $categoryQuestionCounts = $categories->pluck('questions_count');

        // 5. Difficulty Level Distribution
        $levels = Level::withCount('questions')->get();
        $levelNames = $levels->pluck('name');
        $levelQuestionCounts = $levels->pluck('questions_count');

        // 6. Recent Activity
        $recentQuizAttempts = QuizAttempt::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recentExamAttempts = LiveExamAttempt::with(['user', 'exam'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'categoryCount',
            'questionCount',
            'userCount',
            'liveExamCount',
            'totalAttempts',
            'passRate',
            'days',
            'quizData',
            'examData',
            'categoryNames',
            'categoryQuestionCounts',
            'levelNames',
            'levelQuestionCounts',
            'recentQuizAttempts',
            'recentExamAttempts'
        ));
    }
}
