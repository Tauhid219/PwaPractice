<?php

namespace Tests\Feature\Admin;

use App\Models\LiveExam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LiveExamValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup permissions
        Permission::create(['name' => 'access dashboard']);
        Permission::create(['name' => 'manage exams']);
        Permission::create(['name' => 'create exams']);
        Permission::create(['name' => 'edit exams']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['access dashboard', 'manage exams', 'create exams', 'edit exams']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /**
     * Store fails when duration exceeds availability window.
     */
    public function test_store_fails_when_duration_exceeds_window()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.live-exams.store'), [
            'title' => 'Validation Test Exam',
            'description' => 'Test',
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(), // 60 minutes window
            'duration_minutes' => 75, // 75 minutes exceeds 60 minutes
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors(['duration_minutes']);
        $this->assertEquals(0, LiveExam::count());
    }

    /**
     * Store succeeds when duration is within availability window.
     */
    public function test_store_succeeds_when_duration_within_window()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.live-exams.store'), [
            'title' => 'Validation Test Exam',
            'description' => 'Test',
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(), // 60 minutes window
            'duration_minutes' => 60, // exactly 60 minutes
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, LiveExam::count());
        $this->assertEquals('Validation Test Exam', LiveExam::first()->title);
    }

    /**
     * Update fails when duration exceeds availability window.
     */
    public function test_update_fails_when_duration_exceeds_window()
    {
        $exam = LiveExam::create([
            'title' => 'Existing Exam',
            'description' => 'Test',
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.live-exams.update', $exam->id), [
            'title' => 'Updated Exam',
            'description' => 'Test',
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(), // 60 minutes window
            'duration_minutes' => 90, // 90 minutes exceeds 60 minutes
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors(['duration_minutes']);
        $exam->refresh();
        $this->assertEquals('Existing Exam', $exam->title);
        $this->assertEquals(30, $exam->duration_minutes);
    }

    /**
     * Update succeeds when duration is within availability window.
     */
    public function test_update_succeeds_when_duration_within_window()
    {
        $exam = LiveExam::create([
            'title' => 'Existing Exam',
            'description' => 'Test',
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.live-exams.update', $exam->id), [
            'title' => 'Updated Exam',
            'description' => 'Test',
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(), // 60 minutes window
            'duration_minutes' => 45, // fits window
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $exam->refresh();
        $this->assertEquals('Updated Exam', $exam->title);
        $this->assertEquals(45, $exam->duration_minutes);
    }
}
