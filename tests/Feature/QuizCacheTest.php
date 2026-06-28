<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\LiveExam;
use App\Models\LiveExamAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class QuizCacheTest extends TestCase
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

        UserProgress::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'status' => 'active',
        ]);
    }

    /**
     * Test that saving/deleting a category invalidates its cache keys.
     */
    public function test_category_invalidation()
    {
        Cache::put('global_categories_all', 'old_global');
        Cache::put('categories_all', 'old_all');
        Cache::put('category_full_' . $this->category->slug, 'old_full');

        // Update category
        $this->category->update(['name' => 'Updated Name']);

        $this->assertNull(Cache::get('global_categories_all'));
        $this->assertNull(Cache::get('categories_all'));
        $this->assertNull(Cache::get('category_full_' . $this->category->slug));

        // Refill cache
        Cache::put('global_categories_all', 'old_global');
        Cache::put('categories_all', 'old_all');
        Cache::put('category_full_' . $this->category->slug, 'old_full');

        // Delete category
        $this->category->delete();

        $this->assertNull(Cache::get('global_categories_all'));
        $this->assertNull(Cache::get('categories_all'));
        $this->assertNull(Cache::get('category_full_' . $this->category->slug));
    }

    /**
     * Test that saving/deleting a level invalidates category full cache and level questions cache.
     */
    public function test_level_invalidation()
    {
        Cache::put('category_full_' . $this->category->slug, 'old_full');
        Cache::put("level_questions_{$this->level->id}", 'old_questions');

        // Update level
        $this->level->update(['name' => 'Updated Level Name']);

        $this->assertNull(Cache::get('category_full_' . $this->category->slug));
        $this->assertNull(Cache::get("level_questions_{$this->level->id}"));

        // Refill
        Cache::put('category_full_' . $this->category->slug, 'old_full');
        Cache::put("level_questions_{$this->level->id}", 'old_questions');

        // Delete
        $this->level->delete();

        $this->assertNull(Cache::get('category_full_' . $this->category->slug));
        $this->assertNull(Cache::get("level_questions_{$this->level->id}"));
    }

    /**
     * Test that saving/deleting a question invalidates the level questions cache.
     */
    public function test_question_invalidation()
    {
        $question = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
        ]);

        Cache::put("level_questions_{$this->level->id}", 'old_questions');

        // Update question
        $question->update(['question_text' => 'Updated text']);
        $this->assertNull(Cache::get("level_questions_{$this->level->id}"));

        // Refill
        Cache::put("level_questions_{$this->level->id}", 'old_questions');

        // Delete question
        $question->delete();
        $this->assertNull(Cache::get("level_questions_{$this->level->id}"));
    }

    /**
     * Test that quiz controller reads questions from level questions cache key.
     */
    public function test_quiz_controller_reads_from_level_questions_cache()
    {
        // 1. Create a physical question in DB
        $realQuestion = Question::factory()->create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'question_text' => 'Real Question text',
        ]);

        // 2. Put fake questions in level questions cache
        $fakeQuestion = new Question([
            'id' => 9999,
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'question_text' => 'Fake Cached Question text',
            'option_1' => 'A',
            'option_2' => 'B',
            'option_3' => 'C',
            'option_4' => 'D',
            'answer_text' => 'A',
            'correct_answers' => ['A'],
        ]);

        Cache::put("level_questions_{$this->level->id}", collect([$fakeQuestion]), 3600);

        // 3. Request start page
        $response = $this->actingAs($this->user)->get(route('quiz.start', [
            'slug' => $this->category->slug,
            'level' => $this->level->id,
        ]));

        $response->assertStatus(200);
        // Should display the fake cached question, not the real one in DB
        $response->assertSee('Fake Cached Question text');
        $response->assertDontSee('Real Question text');
    }

    /**
     * Test that live exam results caching works and gets invalidated on attempt updates.
     */
    public function test_live_exam_results_page_caching_and_invalidation()
    {
        $exam = LiveExam::factory()->create([
            'start_time' => now()->subHours(2),
            'end_time' => now()->subHour(), // Exam ended
        ]);

        $attempt1 = LiveExamAttempt::create([
            'live_exam_id' => $exam->id,
            'user_id' => $this->user->id,
            'score' => 80,
            'passed' => true,
        ]);

        // Access results to generate cache
        $response = $this->actingAs($this->user)->get(route('live-exams.results', $exam));
        $response->assertStatus(200);

        $version = Cache::get("exam_results_version_{$exam->id}", 1);
        $cacheKey = "exam_results_{$exam->id}_v{$version}_page_1";
        $this->assertTrue(Cache::has($cacheKey));

        // Create a new attempt, which should trigger model booted hook and update version
        // Advance time by 1 second to ensure the timestamp version actually changes in fast tests
        $this->travel(1)->second();
        
        $user2 = User::factory()->create();
        $attempt2 = LiveExamAttempt::create([
            'live_exam_id' => $exam->id,
            'user_id' => $user2->id,
            'score' => 90,
            'passed' => true,
        ]);

        // Old cache key should not match the new version
        $newVersion = Cache::get("exam_results_version_{$exam->id}");
        $this->assertNotEquals($version, $newVersion);
        
        $newCacheKey = "exam_results_{$exam->id}_v{$newVersion}_page_1";
        $this->assertFalse(Cache::has($newCacheKey)); // Should not be generated yet
    }
}
