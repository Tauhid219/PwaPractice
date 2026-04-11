<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\ReadQuestion;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage users', only: ['index', 'show']),
            new Middleware('permission:create users', only: ['create', 'store']),
            new Middleware('permission:edit users', only: ['edit', 'update']),
            new Middleware('permission:delete users', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::with('roles')->orderBy('id', 'desc');

        // Normal students or users without 'manage users' can only see themselves
        if (!auth()->user()->can('manage users')) {
            $query->where('id', auth()->id());
        } else {
            // Permission granted: but restrict super-admin visibility for non-super-admins
            if (!auth()->user()->hasRole('super-admin')) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'super-admin');
                });
            }
        }

        $users = $query->paginate(config('quiz.pagination.admin_users', 50));

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $rolesQuery = Role::query();
        if (!auth()->user()->hasRole('super-admin')) {
            $rolesQuery->where('name', '!=', 'super-admin');
        }
        $roles = $rolesQuery->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array'
        ]);

        // Prevent non-super-admins from assigning super-admin role
        if (!auth()->user()->hasRole('super-admin') && in_array('super-admin', $request->roles)) {
            return redirect()->back()->with('error', 'You cannot assign the Super Admin role.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_admin' => in_array('admin', $request->roles) || in_array('super-admin', $request->roles),
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Security: Users without 'edit users' can ONLY edit their own profile
        if (!auth()->user()->can('edit users')) {
            if (auth()->id() !== $user->id) {
                return redirect()->route('admin.users.index')->with('error', 'You can only edit your own profile.');
            }
        }

        // Don't allow editing super-admin if user is not super-admin
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return redirect()->route('admin.users.index')->with('error', 'You do not have permission to edit a Super Admin.');
        }

        $rolesQuery = Role::query();
        if (!auth()->user()->hasRole('super-admin')) {
            $rolesQuery->where('name', '!=', 'super-admin');
        }
        $roles = $rolesQuery->get();
        
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Display the specified user's progress.
     */
    public function show(User $user)
    {
        // Security: Users without 'manage users' can ONLY view their own progress
        if (!auth()->user()->can('manage users')) {
            if (auth()->id() !== $user->id) {
                return redirect()->route('admin.users.index')->with('error', 'You can only view your own progress.');
            }
        }

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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array' // Nullable for guests
        ]);

        // Security: Users without 'edit users' can ONLY update their own profile
        if (!auth()->user()->can('edit users')) {
            if (auth()->id() !== $user->id) {
                return redirect()->route('admin.users.index')->with('error', 'You can only update your own profile.');
            }
        }

        // Guard against unauthorized modifications of super-admin users
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return redirect()->route('admin.users.index')->with('error', 'You do not have permission to modify a Super Admin.');
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($userData);

        // Role management - Only users with 'edit users' can change roles
        if (auth()->user()->can('edit users')) {
            // Check if the auth user is trying to change their own roles
            if (auth()->id() === $user->id && !auth()->user()->hasRole('super-admin')) {
                if ($request->roles && $request->roles != $user->roles->pluck('name')->toArray()) {
                    return redirect()->route('admin.users.index')->with('error', 'You cannot change your own roles unless you are a Super Admin.');
                }
            }
            
            if ($request->roles) {
                $user->syncRoles($request->roles);
            }
        }

        // Update is_admin flag for backward compatibility
        $user->is_admin = $user->hasRole(['super-admin', 'admin']);
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "Profile updated successfully.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Check if the auth user is trying to delete themselves
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting super-admins if the auth user is not a super-admin
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return redirect()->route('admin.users.index')->with('error', 'You do not have permission to delete a Super Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' deleted successfully.");
    }
}
