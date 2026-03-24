<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;

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

        $questions = $query->paginate(50);
        return view('admin.questions.index', compact('questions', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();
        $levels = \App\Models\Level::all();
        return view('admin.questions.create', compact('categories', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'question_text' => 'required|string',
            'option_1' => 'required|string',
            'option_2' => 'required|string',
            'option_3' => 'required|string',
            'answer_text' => 'required|string',
        ]);

        Question::create($request->all());

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

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'question_text' => 'required|string',
            'option_1' => 'required|string',
            'option_2' => 'required|string',
            'option_3' => 'required|string',
            'answer_text' => 'required|string',
        ]);

        $question->update($request->all());

        return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
        ]);

        try {
            Excel::import(new QuestionImport($request->category_id), $request->file('file'));
            return redirect()->route('admin.questions.index')->with('success', 'Questions imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.index')->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
