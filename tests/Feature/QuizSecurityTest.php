<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class QuizSecurityTest extends TestCase
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
     * Test that the quiz start view does not leak correct answers in the DOM.
     */
    public function test_quiz_taking_view_does_not_contain_correct_answers()
    {
        $question = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'answer_text' => 'লারাভেল',
            'correct_answers' => ['লারাভেল'],
        ]);

        $response = $this->actingAs($this->user)->get(route('quiz.start', [
            'slug' => $this->category->slug,
            'level' => $this->level->id,
        ]));

        $response->assertStatus(200);
        $response->assertDontSee('data-correct');
        $response->assertDontSee('লারাভেল');
    }

    /**
     * Test that the quiz start view contains the CSRF token.
     */
    public function test_quiz_taking_view_contains_csrf_token()
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
        $response->assertSee('_token', false);
    }

    /**
     * Test that the AJAX check answer endpoint requires authentication.
     */
    public function test_ajax_check_answer_requires_auth()
    {
        $response = $this->postJson(route('quiz.check-answer'), [
            'question_id' => 1,
            'answer' => 'test'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test that the AJAX check answer endpoint evaluates answers correctly.
     */
    public function test_ajax_check_answer_returns_correct_response()
    {
        $question = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'answer_text' => 'মক্কা',
            'correct_answers' => ['মক্কা', 'মক্কায়'],
        ]);

        // Case 1: Correct answer
        $response = $this->actingAs($this->user)->postJson(route('quiz.check-answer'), [
            'question_id' => $question->id,
            'answer' => 'মক্কায়'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'is_correct' => true,
                'correct_answer' => 'মক্কা | মক্কায়'
            ]);

        // Case 2: Incorrect answer
        $response = $this->actingAs($this->user)->postJson(route('quiz.check-answer'), [
            'question_id' => $question->id,
            'answer' => 'মদিনা'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'is_correct' => false,
                'correct_answer' => 'মক্কা | মক্কায়'
            ]);
    }

    /**
     * Test that admin user role edit can clear all roles.
     */
    public function test_admin_user_role_sync_can_clear_all_roles()
    {
        Permission::create(['name' => 'access dashboard']);
        Permission::create(['name' => 'edit users']);
        $admin = User::factory()->create();
        
        $role1 = Role::create(['name' => 'editor']);
        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('access dashboard');
        
        $admin->givePermissionTo('edit users');
        $admin->assignRole('admin');

        $targetUser = User::factory()->create();
        $targetUser->assignRole('editor');

        $this->assertTrue($targetUser->hasRole('editor'));

        // Post request with roles = null / empty to sync/clear roles
        $response = $this->actingAs($admin)->put(route('admin.users.update', $targetUser->id), [
            'name' => $targetUser->name,
            'email' => $targetUser->email,
            'roles' => []
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $targetUser->refresh();
        $this->assertEmpty($targetUser->roles);
    }
}
