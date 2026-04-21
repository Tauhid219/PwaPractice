<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Standardized Permissions
        $modules = ['users', 'roles', 'categories', 'questions', 'exams'];
        $actions = ['manage', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::findOrCreate("$action $module");
            }
        }

        // Additional permissions
        Permission::findOrCreate('view results');
        Permission::findOrCreate('access dashboard');
        Permission::findOrCreate('access reports');

        // Create Roles and Assign Permissions

        // Super Admin: gets all permissions
        $roleSuperAdmin = Role::findOrCreate('super-admin');
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Create Default Super Admin User
        $superAdminUser = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
        $superAdminUser->assignRole($roleSuperAdmin);

        // Admin Role: manage everything
        $roleAdmin = Role::findOrCreate('admin');
        $roleAdmin->givePermissionTo(Permission::all());

        // Moderator: manage quizzes and see results
        $roleModerator = Role::findOrCreate('moderator');
        $roleModerator->givePermissionTo([
            'manage categories', 'create categories', 'edit categories',
            'manage questions', 'create questions', 'edit questions',
            'manage exams', 'create exams', 'edit exams', 'view results',
            'manage users',
            'access dashboard',
        ]);

        // Editor: only manage questions and categories
        $roleEditor = Role::findOrCreate('editor');
        $roleEditor->givePermissionTo([
            'manage categories', 'edit categories', 'create categories',
            'manage questions', 'create questions', 'edit questions',
            'access dashboard',
        ]);

        // Guest: can be given specific operational permissions
        $roleGuest = Role::findOrCreate('guest');
        $roleGuest->givePermissionTo([
            'access dashboard',
        ]);

        // Student: limited access for regular users
        $roleStudent = Role::findOrCreate('student');
        // Students don't need admin dashboard access, or they can have whatever guest had before:
        $roleStudent->givePermissionTo([]);

        // Assign roles to other admin users
        $adminUsers = User::where('is_admin', true)->where('email', '!=', 'admin@admin.com')->get();
        foreach ($adminUsers as $user) {
            $user->syncRoles($roleAdmin);
        }

        $regularUsers = User::where('is_admin', false)->get();
        foreach ($regularUsers as $user) {
            $user->syncRoles($roleStudent);
        }
    }
}
