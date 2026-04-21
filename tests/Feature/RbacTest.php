<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup basic roles and permissions
        Permission::create(['name' => 'access dashboard']);
        Permission::create(['name' => 'manage users']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['access dashboard', 'manage users']);

        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'student']);
    }

    /**
     * Guest is redirected to login.
     */
    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Student without permission gets 403.
     */
    public function test_student_cannot_access_admin_dashboard()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('admin.dashboard'));

        // Admin middleware redirects to '/' if permission is missing
        $response->assertRedirect('/');
    }

    /**
     * Admin can access dashboard.
     */
    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /**
     * Super Admin can access everything.
     */
    public function test_super_admin_can_access_admin_dashboard()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super-admin');

        $response = $this->actingAs($superAdmin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    /**
     * Student cannot access user management.
     */
    public function test_student_cannot_access_user_index()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('admin.users.index'));

        $response->assertRedirect('/');
    }
}
