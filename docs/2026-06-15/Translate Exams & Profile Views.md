# Implementation Plan - Translate Exams & Profile Views + Add Profile Edit Link

This plan covers the transition of hardcoded Bengali text in Exams (Live Exams) and Profile views to Laravel localization helpers (`__('Key')`), and adds a Profile Edit link/button to the student's progress/profile dashboard so that they can change their email address and password.

## Proposed Changes

### Translations & Locale JSON Files

#### [MODIFY] [en.json](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/lang/en.json)

#### [MODIFY] [bn.json](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/lang/bn.json)

- Add translation strings for all newly-localized terms in live exams and profile views, ensuring correct English and Bengali mappings.

---

### Live Exams Views

#### [MODIFY] [index.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/index.blade.php)

- Replace Bengali titles, button texts, empty states, and schedule labels with localization helpers.

#### [MODIFY] [show.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/show.blade.php)

- Localize details header, warning list items, schedule labels, remaining countdown labels, and status badges.

#### [MODIFY] [taking.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/taking.blade.php)

- Replace static question counts, timers, control button labels, and alert/confirm scripts (including browser unloading warning messages and visibility warning popups) with localizable strings.
- Adapt MCQ option badges (A, B, C, D in English / ক, খ, গ, ঘ in Bengali) dynamically depending on `App::getLocale()`.

#### [MODIFY] [results.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/results.blade.php)

- Localize subtitle, leaderboard column names, trophy rank emojis/labels, current user markers, empty states, and results text.

---

### Profile & Settings Views

#### [MODIFY] [progress.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/profile/progress.blade.php)

- **Add Settings Link:** Add an edit profile icon button (`⚙️`) in the top-right corner of the gamified header banner pointing to `route('profile.edit')`.
- Localize the rank title levels (Novice/Junior/Senior Genius), metrics labels (Quiz/Streak/XP), achievements badges and their descriptions, quiz history table, and subject-wise progress list.

#### [MODIFY] [edit.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/profile/edit.blade.php)

- Localize the page header, breadcrumbs, study progress card title and button, card headings (Update Profile and Change Password), and button labels.

#### [MODIFY] [update-profile-information-form.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/profile/partials/update-profile-information-form.blade.php)

- Localize labels, email verification warning notes, save button, and success notification message.

#### [MODIFY] [update-password-form.blade.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/profile/partials/update-password-form.blade.php)

- Localize label inputs (Current Password, New Password, Confirm Password), action button, and success notification text.

---

## Verification Plan

### Automated Tests

Run the existing test suite to verify no routing or regression issues:

- `php artisan test`

### Manual Verification

1. Login as a student and click on the "Profile" link in the navbar.
2. Check that the Profile header has an edit/settings button (⚙️) and click it. It should redirect to `/profile` (the edit/settings page).
3. Verify that the Profile Settings page displays input fields for "Name", "Email Address", and "Password" changes, and they successfully save.
4. Toggle the language switch button between English and Bengali in the navbar/bottom nav.
5. Verify that all texts, headings, buttons, columns, and alerts in the **Exams** and **Profile** sections dynamically change to the selected language.
