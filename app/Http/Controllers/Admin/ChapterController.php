<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chapters = Chapter::with('category')->orderBy('category_id')->orderBy('order')->get();
        return view('admin.chapters.index', compact('chapters'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();
        return view('admin.chapters.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $data = $request->all();
        // Generate slug using category slug + chapter name to ensure uniqueness
        $category = Category::find($request->category_id);
        $data['slug'] = Str::slug($category->slug . '-' . $request->name);

        Chapter::create($data);

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.chapters.index');
    }

    public function edit(Chapter $chapter)
    {
        $categories = Category::orderBy('order')->get();
        return view('admin.chapters.edit', compact('chapter', 'categories'));
    }

    public function update(Request $request, Chapter $chapter)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $data = $request->all();
        $category = Category::find($request->category_id);
        $data['slug'] = Str::slug($category->slug . '-' . $request->name);

        $chapter->update($data);

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter updated successfully.');
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter deleted successfully.');
    }
}
