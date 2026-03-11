<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LiveExam;
use App\Models\LiveExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveExamController extends Controller
{
    public function index()
    {
        $activeExams = LiveExam::where('is_active', true)
                               ->orderBy('start_time', 'asc')
                               ->get();
        return view('frontend.live_exam.index', compact('activeExams'));
    }

    public function show(LiveExam $exam)
    {
        if (!$exam->is_active) {
            return redirect()->route('live-exams.index')->with('error', 'এই পরীক্ষাটি বর্তমানে বন্ধ আছে বা উপস্থিত নেই।');
        }
        return view('frontend.live_exam.show', compact('exam'));
    }

    public function join(LiveExam $exam)
    {
        if (!$exam->is_active || now()->isAfter($exam->end_time)) {
             return redirect()->route('live-exams.index')->with('error', 'এই পরীক্ষার সময় শেষ হয়ে গেছে।');
        }

        if (now()->isBefore($exam->start_time)) {
             return redirect()->route('live-exams.index')->with('error', 'পরীক্ষা এখনও শুরু হয়নি।');
        }

        // Check if already attempted
        $attempt = LiveExamAttempt::where('user_id', Auth::id())->where('live_exam_id', $exam->id)->first();
        if ($attempt) {
            return redirect()->route('live-exams.show', $exam)->with('error', 'আপনি ইতিমধ্যে এই পরীক্ষায় অংশ নিয়েছেন।');
        }

        $questions = $exam->questions; // Requires relationship in LiveExam model
        return view('frontend.live_exam.taking', compact('exam', 'questions'));
    }

    public function submit(Request $request, LiveExam $exam)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        $score = 0;
        $totalQuestions = $exam->questions->count();
        
        foreach ($exam->questions as $question) {
            if (isset($request->answers[$question->id]) && 
                trim(strtolower($request->answers[$question->id])) == trim(strtolower($question->answer_text))) {
                $score++;
            }
        }

        $percentage = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;
        $passed = $percentage >= 80;

        $attempt = LiveExamAttempt::create([
            'user_id' => Auth::id(),
            'live_exam_id' => $exam->id,
            'score' => $score,
            'passed' => $passed
        ]);

        return redirect()->route('live-exams.index')->with('success', 'আপনার পরীক্ষা সফলভাবে জমা দেওয়া হয়েছে! স্কোর: ' . $score . '/' . $totalQuestions);
    }
}
