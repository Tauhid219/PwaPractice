<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveExam;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LiveExamController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage exams', only: ['index', 'show', 'results']),
            new Middleware('permission:create exams', only: ['create', 'store']),
            new Middleware('permission:edit exams', only: ['edit', 'update', 'manageQuestions', 'updateQuestions']),
            new Middleware('permission:delete exams', only: ['destroy']),
        ];
    }

    public function index()
    {
        $exams = LiveExam::latest()->paginate(10);
        return view('admin.live_exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.live_exams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        LiveExam::create($validated);

        return redirect()->route('admin.live-exams.index')->with('success', 'Live Exam created successfully.');
    }

    public function show(LiveExam $liveExam)
    {
        return view('admin.live_exams.show', compact('liveExam'));
    }

    public function edit(LiveExam $liveExam)
    {
        return view('admin.live_exams.edit', compact('liveExam'));
    }

    public function update(Request $request, LiveExam $liveExam)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $liveExam->update($validated);

        return redirect()->route('admin.live-exams.index')->with('success', 'Live Exam updated successfully.');
    }

    public function destroy(LiveExam $liveExam)
    {
        $liveExam->delete();
        return redirect()->route('admin.live-exams.index')->with('success', 'Live Exam deleted successfully.');
    }

    public function manageQuestions(Request $request, LiveExam $liveExam)
    {
        $categories = \App\Models\Category::orderBy('order')->get();
        $levels = \App\Models\Level::all();
        $query = \App\Models\Question::with(['category', 'level']);
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $assignedQuestionIds = $liveExam->questions()->pluck('questions.id')->toArray();

        $questions = $query->get()->sortByDesc(function($question) use ($assignedQuestionIds) {
            return in_array($question->id, $assignedQuestionIds);
        });

        // Manually paginate for better control
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 20;
        $currentPageItems = $questions->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $questions = new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems, count($questions), $perPage);
        $questions->setPath($request->url())->appends($request->all());

        return view('admin.live_exams.manage_questions', compact('liveExam', 'questions', 'categories', 'levels', 'assignedQuestionIds'));
    }

    public function updateQuestions(Request $request, LiveExam $liveExam)
    {
        $request->validate([
            'question_ids' => 'array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        $action = $request->input('action'); // 'add' or 'remove'
        $questionIds = $request->input('question_ids', []);

        if ($action === 'add') {
            $liveExam->questions()->syncWithoutDetaching($questionIds);
            $message = count($questionIds) . ' questions added successfully.';
        } elseif ($action === 'remove') {
            $liveExam->questions()->detach($questionIds);
            $message = count($questionIds) . ' questions removed successfully.';
        }

        return back()->with('success', $message ?? 'Updated successfully.');
    }

    public function results(LiveExam $liveExam)
    {
        $attempts = $liveExam->attempts()->with('user')->orderByDesc('score')->orderBy('created_at')->paginate(50);
        return view('admin.live_exams.results', compact('liveExam', 'attempts'));
    }
}
