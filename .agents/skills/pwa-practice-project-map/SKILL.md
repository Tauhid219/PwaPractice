---
name: pwa-practice-project-map
description: Use when working in the PwaPractice repository and you need a fast architecture map before making changes. Covers the Laravel 12 stack, core domains, important folders, high-value docs, and where quiz, live exam, RBAC, progress tracking, and PWA behavior live.
---

# PwaPractice Project Map

Use this skill first when the task is broad, the request is ambiguous, or you need to orient yourself in this repository before editing code.

## Snapshot

- Stack: Laravel 12, PHP 8.2, Breeze auth, Spatie permissions, Bootstrap/AdminLTE, Vite.
- Product shape: Bangla-first quiz learning app with categories, levels, progress tracking, live exams, admin management, and PWA/offline support.
- Frontend has two distinct surfaces:
  - Public/student-facing Bootstrap views under `resources/views/frontend`
  - Admin-facing views under `resources/views/admin`

## Core Domain Areas

- Study catalog:
  - `app/Models/Category.php`
  - `app/Models/Level.php`
  - `app/Models/Question.php`
- Learning progress:
  - `app/Models/UserProgress.php`
  - `app/Models/QuizAttempt.php`
  - `app/Models/ReadQuestion.php`
- Live exams:
  - `app/Models/LiveExam.php`
  - `app/Models/LiveExamQuestion.php`
  - `app/Models/LiveExamAttempt.php`
- Users and access control:
  - `app/Models/User.php`
  - Spatie permission tables via `database/migrations/2026_04_08_124755_create_permission_tables.php`

## Main Request Entry Points

- Public routes: `routes/web.php`
- Auth routes: `routes/auth.php`
- Public/student controllers:
  - `app/Http/Controllers/FrontendController.php`
  - `app/Http/Controllers/Frontend/QuizController.php`
  - `app/Http/Controllers/Frontend/LiveExamController.php`
  - `app/Http/Controllers/ProfileController.php`
- Admin controllers:
  - `app/Http/Controllers/Admin`

## Important Implementation Patterns

- Cached catalog reads exist in `FrontendController`; if you change categories/levels/questions, review cache invalidation.
- Quiz scoring logic is centralized in `app/Services/QuizScoringService.php`.
- Route protection mixes auth, custom middleware, and Spatie permission middleware.
- Seeder-heavy development flow exists; content and permissions often depend on seeders in `database/seeders`.

## High-Value Docs

- Read `docs/walkthrough.md` for project history and feature evolution.
- Read `docs/quiz-and-live-exam-plan.md` or `docs/live-exam-implementation-plan.md` for exam-related work.
- Read `docs/advanced_rbac_plan.md` and `docs/role_permission_plan.md` for access-control changes.
- Read `docs/pwa_plan.md` for install/offline/service-worker work.
- Read `docs/comprehensive-testing-plan.md` for testing expectations.
- Read `docs/optimization-and-scaling-plan.md` for performance-sensitive work.

## First-Pass Checklist

1. Inspect `routes/web.php` to find the user flow you are touching.
2. Inspect the relevant controller, model, migration, and Blade view together.
3. Search tests under `tests/Feature` and `tests/Unit` before changing behavior.
4. Check docs in `docs/` for any plan that already constrains the feature.
5. If data shape changes, inspect seeders and factories before editing.

## Working Style For This Repo

- Prefer small, behavior-preserving changes with test coverage.
- Treat public/student UI and admin UI as separate experiences.
- Preserve Bangla copy and user-facing context when editing content.
- If touching quiz/progress/exam logic, verify both database effects and route access rules.
