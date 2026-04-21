<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitLiveExamRequest;
use App\Jobs\ProcessLiveExamScore;
use App\Models\LiveExam;
use App\Models\LiveExamAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        if (! $exam->is_active) {
            return redirect()->route('live-exams.index')->with('error', 'এই পরীক্ষাটি বর্তমানে বন্ধ আছে বা উপস্থিত নেই।');
        }

        return view('frontend.live_exam.show', compact('exam'));
    }

    public function join(LiveExam $exam)
    {
        if (! $exam->is_active || now()->isAfter($exam->end_time)) {
            return redirect()->route('live-exams.index')->with('error', 'এই পরীক্ষার সময় শেষ হয়ে গেছে।');
        }

        if (now()->isBefore($exam->start_time)) {
            return redirect()->route('live-exams.index')->with('error', 'পরীক্ষা এখনও শুরু হয়নি।');
        }

        // Prevent multiple attempts: Check if already attempted
        if (LiveExamAttempt::where('user_id', Auth::id())->where('live_exam_id', $exam->id)->exists()) {
            return redirect()->route('live-exams.results', $exam)->with('error', 'আপনি ইতিমধ্যে এই পরীক্ষায় অংশ নিয়েছেন।');
        }

        $questions = Cache::remember("exam_questions_{$exam->id}", 3600, function () use ($exam) {
            return $exam->questions;
        });

        return view('frontend.live_exam.taking', compact('exam', 'questions'));
    }

    public function submit(SubmitLiveExamRequest $request, LiveExam $exam)
    {
        // Double check to prevent multiple submissions
        if (LiveExamAttempt::where('user_id', Auth::id())->where('live_exam_id', $exam->id)->exists()) {
            return redirect()->route('live-exams.results', $exam)->with('error', 'আপনার উত্তর ইতিমধ্যে জমা দেওয়া হয়েছে।');
        }

        ProcessLiveExamScore::dispatch($exam, Auth::id(), $request->answers ?? [], $request->input('tab_switches', 0));

        return redirect()->route('live-exams.results', $exam)->with('success', 'আপনার খাতা জমা নেওয়া হয়েছে এবং ফলাফল প্রস্তুত করা হয়েছে!');
    }

    public function results(LiveExam $exam)
    {
        // Make sure exam is ended or user has attempted
        $hasAttempted = LiveExamAttempt::where('user_id', Auth::id())->where('live_exam_id', $exam->id)->exists();

        if (! $hasAttempted && now()->isBefore($exam->end_time)) {
            return redirect()->route('live-exams.show', $exam)->with('error', 'ফলাফল দেখতে আপনাকে পরীক্ষায় অংশগ্রহণ করতে হবে অথবা পরীক্ষা শেষ হওয়া পর্যন্ত অপেক্ষা করতে হবে।');
        }

        $attempts = $exam->attempts()->with('user')->orderByDesc('score')->orderBy('created_at')->paginate(50);

        return view('frontend.live_exam.results', compact('exam', 'attempts', 'hasAttempted'));
    }
}
