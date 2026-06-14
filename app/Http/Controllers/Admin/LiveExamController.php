<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Level;
use App\Models\LiveExam;
use App\Models\Question;
use App\Imports\LiveExamQuestionImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Exports\LiveExamQuestionTemplateExport;

class LiveExamController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage exams', only: ['index', 'show', 'results']),
            new Middleware('permission:create exams', only: ['create', 'store']),
            new Middleware('permission:edit exams', only: ['edit', 'update', 'manageQuestions', 'updateQuestions', 'importQuestions', 'storeQuestion', 'updateSingleQuestion', 'destroyQuestion', 'downloadTemplate']),
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
            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('start_time') && $request->filled('end_time')) {
                        try {
                            $start = \Carbon\Carbon::parse($request->start_time);
                            $end = \Carbon\Carbon::parse($request->end_time);
                            if ($end->gt($start)) {
                                $windowMinutes = $start->diffInMinutes($end);
                                if ($value > $windowMinutes) {
                                    $fail("The exam duration ($value minutes) cannot exceed the exam availability window ($windowMinutes minutes).");
                                }
                            }
                        } catch (\Exception $e) {
                            // Let date validation handle formatting errors
                        }
                    }
                }
            ],
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $liveExam = LiveExam::create($validated);

        return redirect()->route('admin.live-exams.questions.manage', $liveExam->id)->with('success', 'Live Exam created successfully. You can now add or upload questions for this exam.');
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
            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('start_time') && $request->filled('end_time')) {
                        try {
                            $start = \Carbon\Carbon::parse($request->start_time);
                            $end = \Carbon\Carbon::parse($request->end_time);
                            if ($end->gt($start)) {
                                $windowMinutes = $start->diffInMinutes($end);
                                if ($value > $windowMinutes) {
                                    $fail("The exam duration ($value minutes) cannot exceed the exam availability window ($windowMinutes minutes).");
                                }
                            }
                        } catch (\Exception $e) {
                            // Let date validation handle formatting errors
                        }
                    }
                }
            ],
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
        $questions = $liveExam->questions()->paginate(20);

        return view('admin.live_exams.manage_questions', compact('liveExam', 'questions'));
    }

    public function storeQuestion(Request $request, LiveExam $liveExam)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_1' => 'required|string',
            'option_2' => 'required|string',
            'option_3' => 'required|string',
            'option_4' => 'required|string',
            'answer_text' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $options = [
                        trim($request->option_1),
                        trim($request->option_2),
                        trim($request->option_3),
                        trim($request->option_4)
                    ];
                    if (!in_array(trim($value), $options)) {
                        $fail('The correct answer must exactly match one of the four options.');
                    }
                }
            ],
        ]);

        $question = Question::create([
            'category_id' => null,
            'level_id' => null,
            'question_text' => $validated['question_text'],
            'option_1' => $validated['option_1'],
            'option_2' => $validated['option_2'],
            'option_3' => $validated['option_3'],
            'option_4' => $validated['option_4'],
            'answer_text' => trim($validated['answer_text']),
            'correct_answers' => [trim($validated['answer_text'])],
        ]);

        $liveExam->questions()->attach($question->id);

        return redirect()->route('admin.live-exams.questions.manage', $liveExam->id)
            ->with('success', 'Question created and added to Live Exam successfully.');
    }

    public function updateSingleQuestion(Request $request, LiveExam $liveExam, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_1' => 'required|string',
            'option_2' => 'required|string',
            'option_3' => 'required|string',
            'option_4' => 'required|string',
            'answer_text' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $options = [
                        trim($request->option_1),
                        trim($request->option_2),
                        trim($request->option_3),
                        trim($request->option_4)
                    ];
                    if (!in_array(trim($value), $options)) {
                        $fail('The correct answer must exactly match one of the four options.');
                    }
                }
            ],
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'option_1' => $validated['option_1'],
            'option_2' => $validated['option_2'],
            'option_3' => $validated['option_3'],
            'option_4' => $validated['option_4'],
            'answer_text' => trim($validated['answer_text']),
            'correct_answers' => [trim($validated['answer_text'])],
        ]);

        return redirect()->route('admin.live-exams.questions.manage', $liveExam->id)
            ->with('success', 'Question updated successfully.');
    }

    public function destroyQuestion(LiveExam $liveExam, Question $question)
    {
        $liveExam->questions()->detach($question->id);
        $question->delete();

        return redirect()->route('admin.live-exams.questions.manage', $liveExam->id)
            ->with('success', 'Question deleted successfully from Live Exam.');
    }

    public function updateQuestions(Request $request, LiveExam $liveExam)
    {
        $request->validate([
            'question_ids' => 'array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        $action = $request->input('action'); // 'add' or 'remove'
        $questionIds = $request->input('question_ids', []);

        if ($action === 'add') {
            $liveExam->questions()->syncWithoutDetaching($questionIds);
            $message = count($questionIds).' questions added successfully.';
        } elseif ($action === 'remove') {
            $liveExam->questions()->detach($questionIds);
            $message = count($questionIds).' questions removed successfully.';
        }

        return back()->with('success', $message ?? 'Updated successfully.');
    }

    public function importQuestions(Request $request, LiveExam $liveExam)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        try {
            Excel::import(new LiveExamQuestionImport($liveExam), $request->file('file'));

            return redirect()->route('admin.live-exams.questions.manage', $liveExam->id)
                ->with('success', 'Questions imported and assigned to this Live Exam successfully.');
        } catch (\Exception $e) {
            \Log::error('Live Exam Import error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->route('admin.live-exams.questions.manage', $liveExam->id)
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function results(LiveExam $liveExam)
    {
        $attempts = $liveExam->attempts()->with('user')->orderByDesc('score')->orderBy('created_at')->paginate(50);

        return view('admin.live_exams.results', compact('liveExam', 'attempts'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new LiveExamQuestionTemplateExport, 'live_exam_questions_template.xlsx');
    }
}
