# Implementation Plan: Separating Live Exam Questions & Adding Exclusive Question CRUD
**Date:** 2026-06-13

This implementation plan details the changes required to completely separate normal app questions (tied to categories/levels) from Live Exam questions. We will remove the generic question bank selection from the Live Exam management view and introduce direct CRUD (Create, Edit, Delete) for questions exclusive to each Live Exam.

---

## Proposed Changes

### Routes
- Add routes for Live Exam question CRUD operations and downloadable templates in `routes/web.php`:
  - `POST` `/admin/live-exams/{live_exam}/questions/store` -> `storeQuestion`
  - `POST` `/admin/live-exams/{live_exam}/questions/{question}/update` -> `updateSingleQuestion`
  - `DELETE` `/admin/live-exams/{live_exam}/questions/{question}` -> `destroyQuestion`
  - `GET` `/admin/questions/template/download` -> `downloadTemplate` (General questions template)
  - `GET` `/admin/live-exams/template/download` -> `downloadTemplate` (Live Exam questions template)

### Exports
- Create `app/Exports/QuestionTemplateExport.php` to define the headers and structure of the general questions template.
- Create `app/Exports/LiveExamQuestionTemplateExport.php` to define the headers and structure of the Live Exam questions template.

### Controllers
- **`LiveExamController.php`:**
  - **`manageQuestions`:** Update to only query questions assigned to the specific Live Exam (`$liveExam->questions()->paginate(20)`).
  - **`storeQuestion`:** Validate and create a question (`category_id = null`, `level_id = null`, all 4 options required, correct answer must match one of the 4 options, saved as a single-value array), then attach to the exam.
  - **`updateSingleQuestion`:** Validate and update the question details (enforces matching correct answer).
  - **`destroyQuestion`:** Detach the question and delete it from the `questions` table.
  - **`downloadTemplate`:** Generates and returns a downloadable `.xlsx` template file for Live Exam questions.

- **`QuestionController.php`:**
  - **`index`:** Update query to only show normal app questions (`whereNotNull('category_id')->whereNotNull('level_id')`) to prevent standalone Live Exam questions from cluttering the general question list.
  - **`downloadTemplate`:** Generates and returns a downloadable `.xlsx` template file for general questions.

### Views
- **`manage_questions.blade.php`:**
  - Remove the category/level filter form.
  - Update table to show the questions belonging to this exam.
  - Add an "Add Question" card/modal with single correct answer input.
  - Add an "Edit" modal for existing questions with single correct answer input.
  - Add "Delete" button next to each question (sending a DELETE request to `destroyQuestion` route).
  - Updated the Excel Bulk Upload form with detailed Bengali guidelines and a **Download Template** button.
- **`create.blade.php` & `edit.blade.php` (under `admin/questions`):**
  - Removed `option_1`, `option_2`, `option_3`, `option_4` input fields completely since general questions are purely typed input.
  - Updated the helper text under the "Correct Answer" input field.
- **`index.blade.php` (under `admin/questions`):**
  - Removed the line about MCQ options from the Excel import instructions.
  - Added a **Download Template** button inside the import instruction modal.

---

## Verification Plan

### Automated Tests
Create a new test file:
- `tests/Feature/Admin/LiveExamQuestionCrudTest.php`
Verify using:
- `php artisan test`

### Manual Verification
1. **Manage Questions View:** Go to Admin -> Live Exams -> Manage Questions. Check that no general question bank is shown, only assigned questions.
2. **Add Question:** Try adding a single question, verify it appears in the list and works in the exam. Enforce that correct answer must match one of the 4 options.
3. **Excel Upload:** Upload an Excel file, verify questions get imported and assigned.
4. **Edit Question:** Edit a question and verify the updates save.
5. **Delete Question:** Delete a question and verify it is removed from the table and deleted from the database.
6. **Main Questions Index:** Go to Admin -> Questions and verify that Live Exam questions are not displayed there.
7. **Template Downloads:** Click download template in both sections and ensure they produce correctly formatted `.xlsx` files.
