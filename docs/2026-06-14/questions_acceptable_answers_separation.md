# Implementation Plan: Separating Correct Answer and Spelling Variations

This plan details the changes needed to prevent spelling variations (joined with pipe/comma characters) from leaking into the student-facing frontend. We will separate the primary correct answer (displayable on the frontend) from alternative spelling variations in both the Excel import template and the single question management forms (Add / Edit).

---

## User Review Required

> [!IMPORTANT]
> - **Field Separation:** We will introduce a new `acceptable_answers` field in the single question Create/Edit forms to separate the primary correct answer (displayed on the frontend) from spelling variations.
> - **Consistent Flow:** The Controller will combine both fields internally into the `correct_answers` array, ensuring that quiz evaluation remains fully compatible and functional without database migrations.

---

## Proposed Changes

### Requests
#### [MODIFY] [StoreQuestionRequest.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Requests/StoreQuestionRequest.php)
#### [MODIFY] [UpdateQuestionRequest.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Requests/UpdateQuestionRequest.php)
- Add validation rule for `acceptable_answers`: `'acceptable_answers' => 'nullable|string'`.

### Controllers
#### [MODIFY] [QuestionController.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/QuestionController.php)
- Update `store()` and `update()` methods:
  - Extract primary answer from `answer_text`.
  - Extract variations from `acceptable_answers` (comma or pipe-separated), filter out empty strings and duplicate entries.
  - Store the primary answer in `answer_text` column.
  - Merge the primary answer and acceptable variations into the `correct_answers` JSON column.

### Views
#### [MODIFY] [create.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/admin/questions/create.blade.php)
- Add the `acceptable_answers` input field.
- Update labels/placeholders/help-text for `answer_text` to specify it is the primary answer displayed to students.

#### [MODIFY] [edit.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/admin/questions/edit.blade.php)
- Add the `acceptable_answers` input field.
- Set `acceptable_answers` value to alternative spellings extracted from `correct_answers` (all elements except the primary `answer_text`).
- Update labels/placeholders/help-text for `answer_text` to specify it is the primary answer displayed to students.

---

## Verification Plan

### Automated Tests
We will update `tests/Feature/Admin/QuestionManagementTest.php` to add and update feature tests:
1. `test_admin_can_create_question_with_acceptable_answers()`
2. `test_admin_can_update_question_with_acceptable_answers()`
3. `test_admin_can_import_questions_with_acceptable_answers()` (test Excel import mapping)

Run the test suite using:
- `php artisan test`

### Manual Verification
1. Open Admin Panel -> Questions -> Add Single Question. Verify separate inputs for "Correct Answer" and "Acceptable Alternative Spellings (Optional)".
2. Create a question, verify database record has correct values.
3. Edit a question, verify the fields are loaded correctly and update successfully.
4. Try bulk importing an Excel sheet containing `question_text`, `answer_text`, and `acceptable_answers` columns. Verify that questions are imported successfully and student-facing `answer_text` is clean (no pipe characters).
