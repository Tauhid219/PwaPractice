<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected Level $level;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create(['slug' => 'gk']);
        $this->level = Level::factory()->create([
            'category_id' => $this->category->id,
            'order' => 1,
            'is_free' => true,
        ]);

        // Activate level for user
        UserProgress::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'status' => 'active',
        ]);
    }

    /**
     * Test user can access the quiz start page.
     */
    public function test_user_can_access_quiz_start()
    {
        Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('quiz.start', [
            'slug' => $this->category->slug,
            'level' => $this->level->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.quiz.taking');
    }

    /**
     * Test quiz submit with complete correct answers.
     */
    public function test_quiz_submit_succeeds_with_answers()
    {
        $question1 = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'answer_text' => 'মক্কা',
            'correct_answers' => ['মক্কা', 'মক্কায়'],
        ]);

        $question2 = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'answer_text' => '৫',
            'correct_answers' => ['৫', '5'],
        ]);

        $response = $this->actingAs($this->user)->post(route('quiz.submit', [
            'slug' => $this->category->slug,
            'level' => $this->level->id,
        ]), [
            'answers' => [
                $question1->id => 'মক্কায়',
                $question2->id => '5',
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $this->user->id,
            'level_id' => $this->level->id,
            'score' => 2,
            'passed' => true,
        ]);
    }

    /**
     * Test quiz submit succeeds when some answers are null (due to timeout or skipping).
     */
    public function test_quiz_submit_handles_null_or_missing_answers()
    {
        $question1 = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'answer_text' => 'মক্কা',
        ]);

        $question2 = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'answer_text' => '৫',
        ]);

        $response = $this->actingAs($this->user)->post(route('quiz.submit', [
            'slug' => $this->category->slug,
            'level' => $this->level->id,
        ]), [
            'answers' => [
                $question1->id => null, // Timed out or empty
                $question2->id => '৫',  // Correct
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $this->user->id,
            'level_id' => $this->level->id,
            'score' => 1, // 1 correct, 1 null
        ]);
    }

    /**
     * Test quiz submit succeeds when answers array is completely empty/null.
     */
    public function test_quiz_submit_handles_completely_empty_answers()
    {
        Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('quiz.submit', [
            'slug' => $this->category->slug,
            'level' => $this->level->id,
        ]), [
            'answers' => [] // empty
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $this->user->id,
            'level_id' => $this->level->id,
            'score' => 0,
        ]);
    }
}
