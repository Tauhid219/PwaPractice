<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class QuestionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;
    protected Level $level;

    protected function setUp(): void
    {
        parent::setUp();

        // Create categories and levels
        $this->category = Category::create([
            'name' => 'General Knowlege',
            'slug' => 'general-knowledge',
            'icon' => 'fa-globe',
            'description' => 'Test Category',
            'order' => 1
        ]);

        $this->level = Level::create([
            'name' => 'Level 1',
            'category_id' => $this->category->id,
            'order' => 1,
            'required_score_to_unlock' => 80,
            'is_free' => true
        ]);

        // Setup role and permissions
        Permission::create(['name' => 'access dashboard']);
        Permission::create(['name' => 'manage questions']);
        Permission::create(['name' => 'create questions']);
        Permission::create(['name' => 'edit questions']);
        Permission::create(['name' => 'delete questions']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['access dashboard', 'manage questions', 'create questions', 'edit questions', 'delete questions']);

        Role::create(['name' => 'student']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /**
     * Test admin can create a question with acceptable spelling variations.
     */
    public function test_admin_can_create_question_with_acceptable_answers()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.questions.store'), [
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'question_text' => 'What is the capital of Bangladesh?',
            'answer_text' => 'Dhaka',
            'acceptable_answers' => 'ঢাকা |  dhaka  ',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('questions', [
            'question_text' => 'What is the capital of Bangladesh?',
            'answer_text' => 'Dhaka'
        ]);

        $question = Question::where('question_text', 'What is the capital of Bangladesh?')->first();
        $this->assertEquals(['Dhaka', 'ঢাকা', 'dhaka'], $question->correct_answers);
    }

    /**
     * Test option fields are optional when creating a question.
     */
    public function test_options_are_optional_when_creating_question()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.questions.store'), [
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'question_text' => 'Is this a test?',
            'answer_text' => 'Yes',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('questions', [
            'question_text' => 'Is this a test?',
            'answer_text' => 'Yes',
            'option_1' => null,
            'option_2' => null,
            'option_3' => null,
            'option_4' => null
        ]);

        $question = Question::where('question_text', 'Is this a test?')->first();
        $this->assertEquals(['Yes'], $question->correct_answers);
    }

    /**
     * Test admin can update a question with acceptable spelling variations.
     */
    public function test_admin_can_update_question_with_acceptable_answers()
    {
        $question = Question::create([
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'question_text' => 'Original Question',
            'answer_text' => 'Original Answer',
            'correct_answers' => ['Original Answer']
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.questions.update', $question->id), [
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'question_text' => 'Updated Question',
            'answer_text' => 'New Answer',
            'acceptable_answers' => 'বিকল্প উত্তর | দ্বিতীয় বিকল্প',
        ]);

        $response->assertRedirect();

        $question->refresh();
        $this->assertEquals('Updated Question', $question->question_text);
        $this->assertEquals('New Answer', $question->answer_text);
        $this->assertEquals(['New Answer', 'বিকল্প উত্তর', 'দ্বিতীয় বিকল্প'], $question->correct_answers);
    }

    /**
     * Test admin can import questions with acceptable answers.
     */
    public function test_admin_can_import_questions_with_acceptable_answers()
    {
        $header = "question_text,answer_text,acceptable_answers\n";
        $row1 = "\"আমাদের জাতীয় ফলের নাম কি?\",\"কাঁঠাল\",\"কাঠাল|kathal\"\n";
        $row2 = "\"বাংলাদেশের রাজধানীর নাম কি?\",\"ঢাকা\",\"dhaka\"\n";
        
        $content = $header . $row1 . $row2;
        
        $file = UploadedFile::fake()->createWithContent('questions.csv', $content);

        $response = $this->actingAs($this->admin)->post(route('admin.questions.import'), [
            'category_id' => $this->category->id,
            'level_id' => $this->level->id,
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.questions.index', ['category_id' => $this->category->id]));
        
        $this->assertDatabaseHas('questions', [
            'question_text' => 'আমাদের জাতীয় ফলের নাম কি?',
            'answer_text' => 'কাঁঠাল',
            'category_id' => $this->category->id,
            'level_id' => $this->level->id
        ]);

        $question1 = Question::where('question_text', 'আমাদের জাতীয় ফলের নাম কি?')->first();
        $this->assertEquals(['কাঁঠাল', 'কাঠাল', 'kathal'], $question1->correct_answers);

        $this->assertDatabaseHas('questions', [
            'question_text' => 'বাংলাদেশের রাজধানীর নাম কি?',
            'answer_text' => 'ঢাকা',
            'category_id' => $this->category->id,
            'level_id' => $this->level->id
        ]);

        $question2 = Question::where('question_text', 'বাংলাদেশের রাজধানীর নাম কি?')->first();
        $this->assertEquals(['ঢাকা', 'dhaka'], $question2->correct_answers);
    }
}
