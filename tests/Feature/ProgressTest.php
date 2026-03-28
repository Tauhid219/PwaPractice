<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest cannot mark question as read.
     */
    public function test_guest_cannot_mark_as_read()
    {
        $question = Question::factory()->create();

        $response = $this->postJson(route('mark.read'), [
            'question_id' => $question->id,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test user can mark question as read and syncs with DB.
     */
    public function test_user_can_mark_question_as_read()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();

        $response = $this->actingAs($user)->postJson(route('mark.read'), [
            'question_id' => $question->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('read_questions', [
            'user_id' => $user->id,
            'question_id' => $question->id,
        ]);
    }

    /**
     * Test locked level access via middleware.
     */
    public function test_locked_level_middleware_prevents_access()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();

        // Level is not in user_progress table with 'active' or 'completed' status
        // The middleware check.level.access should prevent access to quiz

        $response = $this->actingAs($user)->get(route('quiz.start', [
            'slug' => $category->slug,
            'level' => $level->id,
        ]));

        $response->assertRedirect(); // Should redirect back or elsewhere if locked
    }
}
