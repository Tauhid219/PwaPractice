<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Use pagination since there will be many questions (around 980)
        $questions = Question::with('chapter.category')->orderBy('id', 'desc')->paginate(50);
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $chapters = Chapter::with('category')->orderBy('category_id')->orderBy('order')->get();
        return view('admin.questions.create', compact('chapters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'question_text' => 'required|string',
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
        $chapters = Chapter::with('category')->orderBy('category_id')->orderBy('order')->get();
        return view('admin.questions.edit', compact('question', 'chapters'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'question_text' => 'required|string',
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
            'chapter_id' => 'required|exists:chapters,id',
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
        ]);

        try {
            Excel::import(new QuestionImport($request->chapter_id), $request->file('file'));
            return redirect()->route('admin.questions.index')->with('success', 'Questions imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.index')->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
