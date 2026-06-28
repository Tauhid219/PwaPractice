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
        $categoryId = $level->category_id;

        $progress = UserProgress::where('user_id', auth()->id())
            ->where('category_id', $categoryId)
            ->where('level_id', $level->id)
            ->first();

        // If no progress exists, or status is expressly locked, redirect
        if (! $progress || $progress->status === 'locked') {
            $categorySlug = $request->route('slug') ?? $request->route('category');
            if (!$categorySlug && $level) {
                $level->loadMissing('category');
                $categorySlug = $level->category ? $level->category->slug : 'all';
            }

            return redirect()->route('category.levels', $categorySlug)
                ->with('error', 'এই লেভেলটি এখনও আনলক হয়নি। দয়া করে আগের লেভেলটি পাশ করুন।');
        }

        return $next($request);
    }
}
