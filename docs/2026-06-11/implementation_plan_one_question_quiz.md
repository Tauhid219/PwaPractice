# Implementation Plan: One-Question-One-Answer Quiz (JSON Array), Live Exam 4-Option MCQ & Bilingual Support

This implementation plan details the updated step-by-step changes required to transform the normal quiz into a typed "One Question One Answer" system with spelling variation support using a JSON database column, upgrade the Live Exam to a 4-option MCQ system with bulk excel upload, and add full bilingual (Bangla & English) translation support to the application.

---

## User Review Required

> [!IMPORTANT]
> - **JSON Column for Variations:** We will add a new JSON column `correct_answers` to the `questions` table to store multiple correct answers as a casted array.
> - **Display Backwards Compatibility:** We will keep the `answer_text` column as a plain string storing the primary/display answer. This guarantees that all current views (like study mode or audio TTS) will continue to work without breaking.
> - **Excel Header Alignment:** In the Excel sheets, we will keep the column header as `answer_text` (instead of changing it to `answer`) to maintain compatibility with existing template sheets, but we will allow pipes (`|`) inside the cell to denote multiple acceptable answers.

---

## Phase-by-Phase Roadmap

### [x] Phase 1: Core Database & Model Upgrades
- [x] Create a migration to:
  - Add `option_4` (nullable string) to the `questions` table.
  - Add `correct_answers` (json, nullable) to the `questions` table.
  - Make `category_id` nullable in the `questions` table (to support standalone live exam questions).
- [x] Update `App\Models\Question.php`:
  - Add `option_4` and `correct_answers` to the fillable fields list.
  - Cast `correct_answers` to `array`.
  - Update `shuffledOptions()` to filter out null/empty options, correctly returning up to 4 options.

### [x] Phase 2: Backend Answer Scoring & Evaluation
- [x] Update `App\Services\QuizScoringService.php` `checkAnswer($userAnswer, $correctAnswerArray)`:
  - Normalize user input (trim and lowercase using `mb_strtolower` for Unicode safety).
  - Look up matching answers in the array `correct_answers` of the question. Fall back to comparing with the string `answer_text` if the array is empty or null.
  - Return `true` if any variation matches.

### [x] Phase 3: Localization Setup & Language Switcher
- [x] Create a middleware `App\Http\Middleware\SetLocale` to set the app locale from the session.
- [x] Register `SetLocale` middleware in `bootstrap/app.php` inside the `web` group.
- [x] Add the locale switch route `/locale/{locale}` in `routes/web.php`.
- [x] Create localization files `lang/bn.json` and `lang/en.json` to translate UI elements (Home, Quiz, Profile, Live Exam, Result, score counters, etc.).
- [x] Wrap UI strings in `__('key')` helper inside core student-facing blade views.
- [x] Add the language switcher toggle into the desktop navbar and mobile bottom nav.

### [x] Phase 4: Typed Quiz UI Implementation
- [x] Redesign `resources/views/frontend/quiz/taking.blade.php`:
  - Replace MCQ buttons with a neobrutalist styled text input field.
  - Implement dynamic frontend verification in JavaScript (matching user input against `data-correct` split by `|`).
  - Keep the instant correct/incorrect visual feedback (green/red box and correct/incorrect tone synthesis).
  - List the accepted correct answers in the feedback banner on failure.
- [x] Update study mode questions view (`resources/views/frontend/questions.blade.php`) to show only the primary answer `answer_text` so students see a single clean answer.

### [x] Phase 5: Admin Panel & Seeder Updates
- [x] Update `StoreQuestionRequest.php` and `UpdateQuestionRequest.php` to make `option_1`, `option_2`, `option_3` optional.
- [x] Update `app/Http/Controllers/Admin/QuestionController.php` (store/update methods):
  - Parse `answer_text` input, split by comma (`,`) or pipe (`|`), trim spaces, save to `correct_answers` array, and set `answer_text` to the first element.
- [x] Update `CategorySeeder.php` to seed questions. If the correct answer contains `|`, split it to populate both `correct_answers` (array) and `answer_text` (primary string).

### [x] Phase 6: Live Exam 4-Option MCQ & Bulk Excel Upload
- [x] Update `resources/views/frontend/live_exam/taking.blade.php` to support 4 options (`option_1` to `option_4`), rendering labels `ক`, `খ`, `গ`, `ঘ`.
- [x] Create `App\Imports\LiveExamQuestionImport.php` to handle live exam bulk uploads:
  - Read columns: `question_text`, `option_1`, `option_2`, `option_3`, `option_4`, `answer_text`.
  - Save to `questions` table with `category_id = null` and `level_id = null`.
  - Link questions to the live exam using `live_exam_questions` table.
- [x] Add an import form in `admin/live_exams/manage_questions.blade.php` to allow bulk uploading questions directly to that Live Exam.
- [x] Update `app/Http/Controllers/Admin/LiveExamController.php` to add an `importQuestions` action and route.

---

## Proposed Changes

### Database & Models
#### [NEW] [2026_06_11_000001_add_option_4_and_correct_answers_to_questions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/migrations/2026_06_11_000001_add_option_4_and_correct_answers_to_questions.php)
#### [MODIFY] [Question.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Models/Question.php)

### Backend Scoring
#### [MODIFY] [QuizScoringService.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Services/QuizScoringService.php)

### Localization
#### [NEW] [SetLocale.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Middleware/SetLocale.php)
#### [NEW] [bn.json](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/lang/bn.json)
#### [NEW] [en.json](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/lang/en.json)
#### [MODIFY] [web.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/routes/web.php)
#### [MODIFY] [app.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/bootstrap/app.php)

### Frontend Templates
#### [MODIFY] [navbar.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/layouts/navbar.blade.php)
#### [MODIFY] [bottom_nav.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/layouts/bottom_nav.blade.php)
#### [MODIFY] [taking.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/quiz/taking.blade.php)
#### [MODIFY] [questions.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/questions.blade.php)
#### [MODIFY] [taking.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/taking.blade.php)

### Admin Panel & Imports
#### [NEW] [LiveExamQuestionImport.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Imports/LiveExamQuestionImport.php)
#### [MODIFY] [QuestionController.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/QuestionController.php)
#### [MODIFY] [LiveExamController.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/LiveExamController.php)
#### [MODIFY] [manage_questions.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/admin/live_exams/manage_questions.blade.php)
#### [MODIFY] [CategorySeeder.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/CategorySeeder.php)

---

## Verification Plan

### Automated Tests
Run tests before and after modifications:
- `php artisan test`

### Manual Verification
1. **Database Check:** Verify that migrations run correctly and the new columns are created.
2. **Translation Switch:** Load the home page, toggle language between EN and BN, and check that all UI text updates instantly.
3. **One-Question Quiz:** Start a quiz. Verify that instead of MCQ options, you see a text input field. Type variations of correct answers (e.g. including/excluding "য়") and confirm they evaluate as correct.
4. **Live Exam:** Upload an Excel file with 4 options to a Live Exam. Start the exam and verify that the 4th option is displayed and clickable with the label "ঘ".
