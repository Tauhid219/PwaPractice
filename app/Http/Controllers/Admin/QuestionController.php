<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportQuestionRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Imports\QuestionImport;
use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuestionTemplateExport;

/**
 * Handles administrative management of quiz questions.
 */
class QuestionController extends Controller implements HasMiddleware
{
    /**
     * Define middleware for the controller.
     * 
     * @return array
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage questions', only: ['index', 'show']),
            new Middleware('permission:create questions', only: ['create', 'store', 'import', 'downloadTemplate']),
            new Middleware('permission:edit questions', only: ['edit', 'update']),
            new Middleware('permission:delete questions', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of questions with filtering and pagination.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('order')->get();

        $query = Question::whereNotNull('category_id')
            ->whereNotNull('level_id')
            ->with(['category', 'level'])
            ->orderBy('category_id')
            ->orderBy('id', 'desc');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $totalQuestions = $query->count();
        $questions = $query->paginate(config('quiz.pagination.admin_questions', 50));
        $levels = Level::all();

        return view('admin.questions.index', compact('questions', 'categories', 'levels', 'totalQuestions'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();
        $levels = Level::all();

        return view('admin.questions.create', compact('categories', 'levels'));
    }

    /**
     * Store a newly created question in storage.
     * 
     * @param  \App\Http\Requests\StoreQuestionRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreQuestionRequest $request)
    {
        $data = $request->validated();
        
        $primaryAnswer = trim($request->input('answer_text', ''));
        $correctAnswers = [$primaryAnswer];
        
        if ($request->filled('acceptable_answers')) {
            $alternatives = explode('|', $request->input('acceptable_answers'));
            foreach ($alternatives as $alt) {
                $trimmedAlt = trim($alt);
                if ($trimmedAlt !== '' && !in_array($trimmedAlt, $correctAnswers)) {
                    $correctAnswers[] = $trimmedAlt;
                }
            }
        }
        
        $data['answer_text'] = $primaryAnswer;
        $data['correct_answers'] = $correctAnswers;
        unset($data['acceptable_answers']);

        Question::create($data);

        return redirect()->route('admin.questions.index', ['category_id' => $request->category_id])->with('success', 'Question created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.questions.index');
    }

    public function edit(Question $question)
    {
        $categories = Category::orderBy('order')->get();
        $levels = Level::all();

        return view('admin.questions.edit', compact('question', 'categories', 'levels'));
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $data = $request->validated();
        
        $primaryAnswer = trim($request->input('answer_text', ''));
        $correctAnswers = [$primaryAnswer];
        
        if ($request->filled('acceptable_answers')) {
            $alternatives = explode('|', $request->input('acceptable_answers'));
            foreach ($alternatives as $alt) {
                $trimmedAlt = trim($alt);
                if ($trimmedAlt !== '' && !in_array($trimmedAlt, $correctAnswers)) {
                    $correctAnswers[] = $trimmedAlt;
                }
            }
        }
        
        $data['answer_text'] = $primaryAnswer;
        $data['correct_answers'] = $correctAnswers;
        unset($data['acceptable_answers']);

        $question->update($data);

        return redirect()->route('admin.questions.index', ['category_id' => $question->category_id])->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index', ['category_id' => $question->category_id])->with('success', 'Question deleted successfully.');
    }

    public function import(ImportQuestionRequest $request)
    {

        try {
            Excel::import(new QuestionImport($request->category_id, $request->level_id), $request->file('file'));

            return redirect()->route('admin.questions.index', ['category_id' => $request->category_id])->with('success', 'Questions imported successfully.');
        } catch (\Exception $e) {
            \Log::error('Import error: '.$e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->route('admin.questions.index')->with('error', 'Error importing file: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new QuestionTemplateExport, 'questions_template.xlsx');
    }
}
