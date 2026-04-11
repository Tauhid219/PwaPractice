<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\QuizAttempt;
use App\Models\ReadQuestion;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole(['super-admin', 'admin', 'moderator', 'editor'])) {
            return view('admin.profile.edit', [
                'user' => $user,
            ]);
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the user's study progress.
     */
    public function progress(Request $request): View
    {
        $user = $request->user();
        
        $totalRead = ReadQuestion::where('user_id', $user->id)->count();

        $progressStats = [
            'total_completed_levels' => UserProgress::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_active_levels' => UserProgress::where('user_id', $user->id)->where('status', 'active')->count(),
        ];

        $quizAttempts = QuizAttempt::where('user_id', $user->id)
            ->with(['category', 'level'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        $userProgress = UserProgress::where('user_id', $user->id)
            ->with(['category', 'level'])
            ->orderBy('category_id')
            ->orderBy('level_id')
            ->get();

        return view('profile.progress', compact('user', 'totalRead', 'progressStats', 'quizAttempts', 'userProgress'));
    }
}
