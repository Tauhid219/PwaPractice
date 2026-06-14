<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Level;
use App\Models\LiveExam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LiveExamQuestionCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected LiveExam $liveExam;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Live Exam
        $this->liveExam = LiveExam::create([
            'title' => 'Weekly Contest',
            'description' => 'Test Exam Description',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        // Setup role and permissions
        Permission::create(['name' => 'access dashboard']);
        Permission::create(['name' => 'manage exams']);
        Permission::create(['name' => 'edit exams']);
        Permission::create(['name' => 'manage questions']);
        Permission::create(['name' => 'create questions']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['access dashboard', 'manage exams', 'edit exams', 'manage questions', 'create questions']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /**
     * Test manageQuestions page lists only questions assigned to the exam.
     */
    public function test_admin_can_view_assigned_questions()
    {
        // Question assigned to live exam
        $assignedQ = Question::create([
            'question_text' => 'What is 10 + 10?',
            'option_1' => '10',
            'option_2' => '20',
            'option_3' => '30',
            'option_4' => '40',
            'answer_text' => '20',
            'correct_answers' => ['20'],
        ]);
        $this->liveExam->questions()->attach($assignedQ->id);

        // Standalone question not assigned to this live exam
        $unassignedQ = Question::create([
            'question_text' => 'What is 5 + 5?',
            'option_1' => '5',
            'option_2' => '10',
            'option_3' => '15',
            'option_4' => '20',
            'answer_text' => '10',
            'correct_answers' => ['10'],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.live-exams.questions.manage', $this->liveExam->id));

        $response->assertStatus(200);
        $response->assertSee('What is 10 + 10?');
        $response->assertDontSee('What is 5 + 5?');
    }

    /**
     * Test admin can create/store a single question for a Live Exam.
     */
    public function test_admin_can_add_single_question_to_live_exam()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.live-exams.questions.store', $this->liveExam->id), [
            'question_text' => 'What is the capital of France?',
            'option_1' => 'London',
            'option_2' => 'Paris',
            'option_3' => 'Rome',
            'option_4' => 'Berlin',
            'answer_text' => 'Paris', // exactly matches one of the options
        ]);

        $response->assertRedirect(route('admin.live-exams.questions.manage', $this->liveExam->id));
        $response->assertSessionHas('success');

        // Check if question exists in DB
        $this->assertDatabaseHas('questions', [
            'question_text' => 'What is the capital of France?',
            'option_1' => 'London',
            'option_2' => 'Paris',
            'option_3' => 'Rome',
            'option_4' => 'Berlin',
            'answer_text' => 'Paris',
            'category_id' => null,
            'level_id' => null,
        ]);

        // Fetch question to check correct_answers array
        $question = Question::where('question_text', 'What is the capital of France?')->first();
        $this->assertEquals(['Paris'], $question->correct_answers);

        // Check relationship
        $this->assertTrue($this->liveExam->questions->contains($question->id));
    }

    /**
     * Test admin cannot create a single question with an answer not matching any option.
     */
    public function test_admin_cannot_add_question_with_invalid_answer()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.live-exams.questions.store', $this->liveExam->id), [
            'question_text' => 'What is the capital of France?',
            'option_1' => 'London',
            'option_2' => 'Paris',
            'option_3' => 'Rome',
            'option_4' => 'Berlin',
            'answer_text' => 'Madrid', // does not match any of the options
        ]);

        $response->assertSessionHasErrors(['answer_text']);
        $this->assertCount(0, $this->liveExam->questions);
    }

    /**
     * Test admin can update a specific Live Exam question.
     */
    public function test_admin_can_update_live_exam_question()
    {
        $question = Question::create([
            'question_text' => 'Old Question Text',
            'option_1' => 'A',
            'option_2' => 'B',
            'option_3' => 'C',
            'option_4' => 'D',
            'answer_text' => 'A',
            'correct_answers' => ['A'],
        ]);
        $this->liveExam->questions()->attach($question->id);

        $response = $this->actingAs($this->admin)->post(route('admin.live-exams.questions.update-single', [$this->liveExam->id, $question->id]), [
            'question_text' => 'New Question Text',
            'option_1' => 'X',
            'option_2' => 'Y',
            'option_3' => 'Z',
            'option_4' => 'W',
            'answer_text' => 'X', // exactly matches option_1
        ]);

        $response->assertRedirect(route('admin.live-exams.questions.manage', $this->liveExam->id));
        $response->assertSessionHas('success');

        // Check if question is updated in DB
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'New Question Text',
            'option_1' => 'X',
            'option_2' => 'Y',
            'option_3' => 'Z',
            'option_4' => 'W',
            'answer_text' => 'X',
        ]);

        $question->refresh();
        $this->assertEquals(['X'], $question->correct_answers);
    }

    /**
     * Test admin cannot update a question with an answer not matching any option.
     */
    public function test_admin_cannot_update_question_with_invalid_answer()
    {
        $question = Question::create([
            'question_text' => 'Old Question Text',
            'option_1' => 'A',
            'option_2' => 'B',
            'option_3' => 'C',
            'option_4' => 'D',
            'answer_text' => 'A',
            'correct_answers' => ['A'],
        ]);
        $this->liveExam->questions()->attach($question->id);

        $response = $this->actingAs($this->admin)->post(route('admin.live-exams.questions.update-single', [$this->liveExam->id, $question->id]), [
            'question_text' => 'New Question Text',
            'option_1' => 'X',
            'option_2' => 'Y',
            'option_3' => 'Z',
            'option_4' => 'W',
            'answer_text' => 'V', // does not match any of the options
        ]);

        $response->assertSessionHasErrors(['answer_text']);
        
        $question->refresh();
        $this->assertEquals('Old Question Text', $question->question_text);
    }

    /**
     * Test admin can delete a specific Live Exam question (which also deletes the question record).
     */
    public function test_admin_can_delete_live_exam_question()
    {
        $question = Question::create([
            'question_text' => 'Question to Delete',
            'option_1' => 'A',
            'option_2' => 'B',
            'option_3' => 'C',
            'option_4' => 'D',
            'answer_text' => 'A',
            'correct_answers' => ['A'],
        ]);
        $this->liveExam->questions()->attach($question->id);

        $response = $this->actingAs($this->admin)->delete(route('admin.live-exams.questions.destroy', [$this->liveExam->id, $question->id]));

        $response->assertRedirect(route('admin.live-exams.questions.manage', $this->liveExam->id));
        $response->assertSessionHas('success');

        // Check the question record has been deleted
        $this->assertDatabaseMissing('questions', [
            'id' => $question->id,
        ]);

        // Check pivot is cleared
        $this->assertCount(0, $this->liveExam->questions);
    }

    /**
     * Test general questions index page does not display standalone live exam questions.
     */
    public function test_admin_questions_index_does_not_list_standalone_questions()
    {
        // Normal app question (has category and level)
        $category = Category::create(['name' => 'GK', 'slug' => 'gk']);
        $level = Level::create(['category_id' => $category->id, 'name' => 'Level 1', 'level_number' => 1]);
        $normalQ = Question::create([
            'category_id' => $category->id,
            'level_id' => $level->id,
            'question_text' => 'Normal Question Text',
            'answer_text' => 'Test',
            'correct_answers' => ['Test'],
        ]);

        // Standalone question (used in live exam)
        $standaloneQ = Question::create([
            'question_text' => 'Standalone Question Text',
            'option_1' => 'A',
            'option_2' => 'B',
            'option_3' => 'C',
            'option_4' => 'D',
            'answer_text' => 'A',
            'correct_answers' => ['A'],
        ]);
        $this->liveExam->questions()->attach($standaloneQ->id);

        $response = $this->actingAs($this->admin)->get(route('admin.questions.index'));

        $response->assertStatus(200);
        $response->assertSee('Normal Question Text');
        $response->assertDontSee('Standalone Question Text');
    }

    /**
     * Test admin can download general questions template.
     */
    public function test_admin_can_download_general_questions_template()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.questions.template'));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=questions_template.xlsx');
    }

    /**
     * Test admin can download live exam questions template.
     */
    public function test_admin_can_download_live_exam_questions_template()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.live-exams.questions.template'));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=live_exam_questions_template.xlsx');
    }
}
