<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportQuestionRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Imports\QuestionImport;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('order')->get();

        $query = Question::with('category')->orderBy('category_id')->orderBy('id', 'desc');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $questions = $query->paginate(config('quiz.pagination.admin_questions', 50));

        return view('admin.questions.index', compact('questions', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();
        $levels = \App\Models\Level::all();

        return view('admin.questions.create', compact('categories', 'levels'));
    }

    public function store(StoreQuestionRequest $request)
    {
        Question::create($request->validated());

        return redirect()->route('admin.questions.index')->with('success', 'Question created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.questions.index');
    }

    public function edit(Question $question)
    {
        $categories = Category::orderBy('order')->get();
        $levels = \App\Models\Level::all();

        return view('admin.questions.edit', compact('question', 'categories', 'levels'));
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $question->update($request->validated());

        return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully.');
    }

    public function import(ImportQuestionRequest $request)
    {

        try {
            Excel::queueImport(new QuestionImport($request->category_id), $request->file('file'));

            return redirect()->route('admin.questions.index')->with('success', 'Questions import started in the background.');
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.index')->with('error', 'Error importing file: '.$e->getMessage());
        }
    }
}
