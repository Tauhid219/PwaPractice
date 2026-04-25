<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Models\Category;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LevelController extends Controller implements HasMiddleware
{
    /**
     * Define middleware for the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage levels', only: ['index', 'show']),
            new Middleware('permission:create levels', only: ['create', 'store']),
            new Middleware('permission:edit levels', only: ['edit', 'update']),
            new Middleware('permission:delete levels', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the levels.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('order')->get();

        $query = Level::with(['category'])->withCount('questions')->orderBy('category_id')->orderBy('order');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $levels = $query->paginate(config('quiz.pagination.admin_levels', 50));

        return view('admin.levels.index', compact('levels', 'categories'));
    }

    /**
     * Show the form for creating a new level.
     */
    public function create()
    {
        $categories = Category::orderBy('order')->get();

        return view('admin.levels.create', compact('categories'));
    }

    /**
     * Store a newly created level in storage.
     */
    public function store(StoreLevelRequest $request)
    {
        Level::create($request->validated());

        return redirect()->route('admin.levels.index', ['category_id' => $request->category_id])
            ->with('success', 'Level created successfully.');
    }

    /**
     * Display the specified level.
     */
    public function show(Level $level)
    {
        return redirect()->route('admin.levels.index');
    }

    /**
     * Show the form for editing the specified level.
     */
    public function edit(Level $level)
    {
        $categories = Category::orderBy('order')->get();

        return view('admin.levels.edit', compact('level', 'categories'));
    }

    /**
     * Update the specified level in storage.
     */
    public function update(UpdateLevelRequest $request, Level $level)
    {
        $level->update($request->validated());

        return redirect()->route('admin.levels.index', ['category_id' => $level->category_id])
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified level from storage.
     */
    public function destroy(Level $level)
    {
        if ($level->questions()->count() > 0) {
            return back()->with('error', 'Cannot delete level as it has associated questions.');
        }

        $level->delete();

        return redirect()->route('admin.levels.index', ['category_id' => $level->category_id])
            ->with('success', 'Level deleted successfully.');
    }
}
