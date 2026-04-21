<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Globally bypass CSRF in tests to simplify feature testing
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * Helper to setup basic permissions for tests that trigger RBAC checks.
     */
    protected function setupPermissions()
    {
        Permission::firstOrCreate(['name' => 'access dashboard']);
        Permission::firstOrCreate(['name' => 'manage users']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(['access dashboard', 'manage users']);

        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'student']);
    }
}
