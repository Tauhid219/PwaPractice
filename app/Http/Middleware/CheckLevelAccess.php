<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevelAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $level = $request->route('level');

        if (!$level) {
            return $next($request);
        }

        if (is_string($level)) {
            $level = \App\Models\Level::find($level);
        }

        // If level is free, allow access
        if ($level && $level->is_free) {
            return $next($request);
        }

        // Check user progress
        $progress = \App\Models\UserProgress::where('user_id', auth()->id())
            ->where('level_id', $level->id)
            ->first();

        if (!$progress || $progress->status === 'locked') {
            return redirect()->route('category.levels', $request->route('category') ?? $level->questions()->first()->category->slug)
                             ->with('error', 'এই লেভেলটি এখনও লক করা আছে। পূর্ববর্তী লেভেল পাস করুন।');
        }

        return $next($request);
    }
}
