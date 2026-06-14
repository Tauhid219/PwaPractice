<?php

namespace Tests\Feature\Admin;

use App\Models\LiveExam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LiveExamQuestionImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected LiveExam $liveExam;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Live Exam
        $this->liveExam = LiveExam::create([
            'title' => 'Monthly Contest',
            'description' => 'Test Exam',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        // Setup role and permissions
        Permission::create(['name' => 'access dashboard']);
        Permission::create(['name' => 'manage exams']);
        Permission::create(['name' => 'edit exams']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['access dashboard', 'manage exams', 'edit exams']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /**
     * Test admin can import questions for live exam using Excel mock upload.
     */
    public function test_admin_can_import_questions_for_live_exam()
    {
        // Fake Maatwebsite Excel facades so it doesn't try to read actual binary files,
        // or write a real CSV stream to verify the database.
        // Let's create a real temporary CSV stream to test the database insertion directly.
        $header = "question_text,option_1,option_2,option_3,option_4,answer_text\n";
        $row1 = "\"Who is the president?\",\"A\",\"B\",\"C\",\"D\",\"A\"\n";
        $row2 = "\"What is 2+2?\",\"3\",\"4\",\"5\",\"6\",\"4\"\n";
        
        $content = $header . $row1 . $row2;
        
        $file = UploadedFile::fake()->createWithContent('live_exam_questions.csv', $content);

        $response = $this->actingAs($this->admin)->post(route('admin.live-exams.questions.import', $this->liveExam->id), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.live-exams.questions.manage', $this->liveExam->id));
        $response->assertSessionHas('success');

        // Check if questions are in database
        $this->assertDatabaseHas('questions', [
            'question_text' => 'Who is the president?',
            'option_1' => 'A',
            'option_2' => 'B',
            'option_3' => 'C',
            'option_4' => 'D',
            'answer_text' => 'A',
            'category_id' => null,
            'level_id' => null
        ]);

        $this->assertDatabaseHas('questions', [
            'question_text' => 'What is 2+2?',
            'option_1' => '3',
            'option_2' => '4',
            'option_3' => '5',
            'option_4' => '6',
            'answer_text' => '4',
            'category_id' => null,
            'level_id' => null
        ]);

        // Check pivot linking
        $this->assertCount(2, $this->liveExam->questions);
        $this->assertEquals(['A'], $this->liveExam->questions[0]->correct_answers);
    }
}
