<?php

namespace Tests\Feature;

use App\Models\LiveExam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveExamTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupPermissions();
    }

    /**
     * Guest redirected to login when accessing live exams.
     */
    public function test_guest_redirected_from_live_exams()
    {
        $response = $this->get(route('live-exams.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Auth user can see live exams list.
     */
    public function test_user_can_access_live_exams_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('live-exams.index'));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.live_exam.index');
    }

    /**
     * User can join an active live exam.
     */
    public function test_user_can_join_active_exam()
    {
        $user = User::factory()->create();
        $startTime = now()->subMinutes(5);
        $duration = 30;
        $exam = LiveExam::factory()->create([
            'start_time' => $startTime,
            'end_time' => (clone $startTime)->addMinutes($duration),
            'duration_minutes' => $duration,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('live-exams.join', $exam->id));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.live_exam.taking');
    }

    /**
     * User cannot join a finished exam.
     */
    public function test_user_cannot_join_finished_exam()
    {
        $user = User::factory()->create();
        $startTime = now()->subMinutes(60);
        $duration = 30;
        $exam = LiveExam::factory()->create([
            'start_time' => $startTime,
            'end_time' => (clone $startTime)->addMinutes($duration),
            'duration_minutes' => $duration,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('live-exams.join', $exam->id));

        // Assuming it redirects with error if finished
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
