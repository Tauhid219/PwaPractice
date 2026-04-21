<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Level;
use App\Models\UserProgress;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevelAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $level = $request->route('level');

        if (! $level) {
            return $next($request);
        }

        if (is_string($level)) {
            $level = Level::find($level);
        }

        // Make sure user is logged in
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // If level is free, allow access without checking progress
        if ($level && $level->is_free) {
            return $next($request);
        }

        // Check user progress for paid or higher levels
        $category = $request->route('category');
        $categoryId = $category instanceof Category ? $category->id : null;

        if (! $categoryId) {
            // Try to find category from slug or level questions
            $slug = $request->route('slug') ?? $request->route('category');
            if ($slug && is_string($slug)) {
                $categoryObj = Category::where('slug', $slug)->first();
                $categoryId = $categoryObj ? $categoryObj->id : null;
            }

            if (! $categoryId && $level->questions()->first()) {
                $categoryId = $level->questions()->first()->category_id;
            }
        }

        $progress = UserProgress::where('user_id', auth()->id())
            ->where('category_id', $categoryId)
            ->where('level_id', $level->id)
            ->first();

        // If no progress exists, or status is expressly locked, redirect
        if (! $progress || $progress->status === 'locked') {
            $categorySlug = $request->route('category') ?? ($level->questions()->first() ? $level->questions()->first()->category->slug : 'all');

            return redirect()->route('category.levels', $categorySlug)
                ->with('error', 'এই লেভেলটি এখনও আনলক হয়নি। দয়া করে আগের লেভেলটি পাশ করুন।');
        }

        return $next($request);
    }
}
