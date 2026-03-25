<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\ReadQuestion;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(config('quiz.pagination.admin_users', 50));

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user's progress.
     */
    public function show(User $user)
    {
        $totalRead = ReadQuestion::where('user_id', $user->id)->count();

        $progressStats = [
            'total_completed_levels' => UserProgress::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_active_levels' => UserProgress::where('user_id', $user->id)->where('status', 'active')->count(),
        ];

        $quizAttempts = QuizAttempt::where('user_id', $user->id)
            ->with(['level', 'level.questions'])
            ->orderBy('id', 'desc')
            ->paginate(config('quiz.pagination.admin_user_attempts', 15));

        $userProgress = UserProgress::where('user_id', $user->id)
            ->with(['category', 'level'])
            ->orderBy('category_id')
            ->orderBy('level_id')
            ->get();

        return view('admin.users.show', compact('user', 'totalRead', 'progressStats', 'quizAttempts', 'userProgress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Check if the auth user is trying to remove their own admin access
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot change your own admin status.');
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $action = $user->is_admin ? 'made an Admin' : 'removed from Admin';

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' was successfully {$action}.");
    }
}
