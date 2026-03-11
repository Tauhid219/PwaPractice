# Quiz Logic & Live Exam Implementation Plan

This document outlines the implementation plan for **Level 5 (Quiz Logic & Live Exam)** of the Genius Kids Quiz Guidebook App.

*Note: Payment integration (Level 6) will be handled separately in a later phase.*

## Proposed Changes

### 1. Backend Logic & Routes (Controllers and Web Routes)

We need to create the controllers and routes to handle quiz attempts and submissions, along with a separate module for Live Exams.

#### [NEW] `app/Http/Controllers/Frontend/QuizController.php`
- `start(Level $level)`: Initializes a quiz session for a specific level. Validates if the user has access (e.g., has completed the previous level).
- `submit(Request $request, Level $level)`: Processes the submitted answers, calculates the score, updates the `QuizAttempt` table. If the score meets the passing criteria, it updates `UserProgress` to unlock the next level.
- `result(QuizAttempt $attempt)`: Displays the result screen of a quiz attempt (score, pass/fail status).

#### [NEW] `app/Http/Controllers/Frontend/LiveExamController.php`
*Note: Live Exam is built as a completely separate section from regular level quizzes.*
- `index()`: Lists upcoming and currently active live exams.
- `show(LiveExam $exam)`: Shows details, rules, and entry conditions for a specific live exam.
- `join(LiveExam $exam)`: Enters the user into the live exam session.
- `submit(Request $request, LiveExam $exam)`: Submits the live exam and calculates the score.

#### [MODIFY] `routes/web.php`
- Add routes for `QuizController` under an `auth` middleware group (e.g., `/category/{slug}/level/{level}/quiz`, `/quiz/{attempt}/result`).
- Add routes for `LiveExamController` (e.g., `/live-exams`, `/live-exams/{exam}`).

### 2. Database & Models

We need new tables to manage Live Exams (separate from regular quizzes).

#### [NEW] `database/migrations/xxxx_xx_xx_create_live_exams_table.php`
- `title` (string)
- `description` (text)
- `start_time` (datetime)
- `end_time` (datetime)
- `duration_minutes` (integer)
- `is_active` (boolean)

#### [NEW] `database/migrations/xxxx_xx_xx_create_live_exam_questions_table.php`
- `live_exam_id` (foreign key)
- `question_id` (foreign key)

#### [NEW] Models
- `app/Models/LiveExam.php`

### 3. Frontend Views (Blade Templates)

#### [NEW] `resources/views/frontend/quiz/start.blade.php`
- Landing page to start a quiz.
- Needs to include interaction sounds (e.g., 'Pop' on clicking start).

#### [NEW] `resources/views/frontend/quiz/taking.blade.php`
- The actual quiz interface, displaying questions sequentially or all at once.
- **Includes Logic for UI Sound:** Immediate sound feedback on selecting an option ('Ting' for correct, 'Buzzer' for wrong).

#### [NEW] `resources/views/frontend/quiz/result.blade.php`
- Shows the final score and pass/fail status.
- Button to proceed to the next level (if passed) or retry (if failed).

#### [NEW] Live Exam Views
- `resources/views/frontend/live_exam/index.blade.php`: List of exams.
- `resources/views/frontend/live_exam/show.blade.php`: Exam details.
- `resources/views/frontend/live_exam/taking.blade.php`: The live exam interface.

### 4. Middleware for Access Control

#### [NEW] `app/Http/Middleware/CheckLevelAccess.php`
- Ensures a user can only access a quiz or level content if they have unlocked it (i.e., they have a corresponding entry in `user_progress` with status `active` or `completed`, or it's implicitly the first level).

---

## Verification Plan

### Automated Tests
- Create tests for score calculation logic in `QuizController` and `LiveExamController`.

### Manual Verification
1. **Regular Quiz Flow:**
   - Log in as a user.
   - Go to a category and select an unlocked level.
   - Proceed to the Quiz section.
   - Submit answers and verify the score calculation.
   - Verify that passing unlocks the next level.
   - Verify sound effects during the quiz.
2. **Access Control:**
   - Try to access a locked level's URL directly and ensure it blocks access.
3. **Live Exam Flow:**
   - Go to the Live Exams section.
   - Verify active exams can be joined and submitted correctly.
