# Implementation Plan for Phase 4: Level-based Learning System (Revised)

**তারিখ:** ০৭ মার্চ ২০২৬

This plan details the implementation of the core level-based learning flow and role-based access.

## Goal Description
Transform the application into a structured, step-by-step learning journey. 
1. **Remove Chapters:** The hierarchy goes directly from Category -> Levels -> Questions.
2. **Authentication & Roles:** Protect the learning routes so only logged-in users can access the questions (except the first few). Admins are redirected to the admin panel, students to the frontend profile.
3. **Level Selection:** Introduce a new view that lists the levels within a chosen Category.
4. **Accordion UX:** Use an Accordion style for questions (5 per level) instead of the Flashcard layout.
5. **Progress Tracking:** Track which questions the user has read using `LocalStorage` state to prevent database overload. "Take Quiz" appears when the level is completed.
6. **User Management:** Provide an interface for Admins to view all registered users and assign/revoke Admin roles.

## UX Decisions Based on Client Feedback
- **Free vs Paid Question Access**: Category lists remain public. The first level's questions can be viewed. Beyond that, the user hit a Login constraint.
- **Hierarchy Pivot:** "অধ্যায় রাখা দরকার নেই" (Chapters are not needed). We removed `chapters` from the database and UI.
- **Student Profile:** Students interact entirely within the Frontend Bootstrap theme (`/profile`) and never see the default Breeze Tailwind dashboard.

## Proposed Changes

### Database Refactoring
- Drop `chapters` table.
- Remove `chapter_id` from `questions` table and replace with `category_id`.
- Update Models (`Question` belongsTo `Category`; `Category` hasMany `Question`).

### Routes
**`routes/web.php`**
- Update level route: `category/{slug}/level/{levelId}`
- Add resource route `admin.users` for user management.

### Controllers
**`app/Http/Controllers/FrontendController.php`**
- Modify `categoryLevels($slug)` to list levels under a category.
- Modify `levelQuestions($categorySlug, $levelId)` to list questions within a level based on category_id instead of chapter_id.
- Limit questions if the user isn't logged in.

**`app/Http/Controllers/Admin/UserController.php` (NEW)**
- `index()` to list all users.
- `update()` to toggle `is_admin` status.

### Views
**`resources/views/frontend/levels.blade.php`**
- Show Levels directly for the selected Category.
**`resources/views/frontend/questions.blade.php`**
- Restore the accordion format, augmented with Level and progression logic.
**`resources/views/profile/*`**
- Rebuild user profile with Bootstrap matching the `frontend.layouts.master` layout.
**`resources/views/admin/users/index.blade.php` (NEW)**
- Admin-only view to manage user roles.
