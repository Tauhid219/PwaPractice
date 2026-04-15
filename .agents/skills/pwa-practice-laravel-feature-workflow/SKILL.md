---
name: pwa-practice-laravel-feature-workflow
description: Use when implementing or refactoring backend Laravel features in the PwaPractice repository. Covers the expected workflow across routes, controllers, models, services, validation, caching, seeders, and Blade views for quiz, progress, category, and profile-related changes.
---

# PwaPractice Laravel Feature Workflow

Use this skill for normal feature work in this repo when the change touches Laravel application behavior.

## Default Build Path

1. Find the route in `routes/web.php`.
2. Identify the controller action and any middleware on that route.
3. Inspect related models, relationships, and existing query shape.
4. Decide whether logic belongs in controller, model, or `app/Services`.
5. Update Blade views only after backend contract is clear.
6. Add or update tests.

## Repository Conventions

- Keep controllers fairly lean.
- Put reusable scoring or domain logic into `app/Services`.
- Reuse Eloquent relationships instead of duplicating query logic across controllers.
- Prefer explicit validation before writes.
- When a feature affects admin CRUD, update both controller behavior and the admin Blade views.

## Files Commonly Touched Together

- Catalog or quiz flow:
  - `routes/web.php`
  - `app/Http/Controllers/FrontendController.php`
  - `app/Http/Controllers/Frontend/QuizController.php`
  - `app/Models/Category.php`
  - `app/Models/Level.php`
  - `app/Models/Question.php`
  - `resources/views/frontend`
- Profile/progress flow:
  - `app/Http/Controllers/ProfileController.php`
  - `app/Models/UserProgress.php`
  - `app/Models/ReadQuestion.php`
  - `resources/views/admin/profile`
- Shared business logic:
  - `app/Services/QuizScoringService.php`

## Validation and Security Expectations

- Validate request data before any database write.
- Check authorization and middleware implications for every new route.
- For admin-side changes, review Spatie permission requirements, not just auth.
- Avoid trusting client-provided IDs without existence validation and ownership/permission checks.

## Caching Notes

- `FrontendController` uses `Cache::rememberForever` for category and level data.
- If a change can alter category, level, or question visibility, account for cache invalidation or refresh strategy.
- Do not add new permanent cache keys casually; keep naming consistent and easy to clear.

## Database Change Workflow

- When data shape changes:
  - Add or update migration
  - Update model fillable/casts/relationships if needed
  - Update factory
  - Update seeder
  - Update tests
- If the change impacts seeded permissions or default content, inspect `database/seeders/RolesAndPermissionsSeeder.php` and related seeders.

## When To Use Services

- Use `app/Services` for business rules that:
  - are reused in multiple controllers
  - have meaningful domain logic
  - should be unit tested independently
- Keep trivial CRUD orchestration in controllers unless duplication starts to grow.

## Verification

- Prefer `php artisan test` for final verification.
- If only a narrow domain changed, run focused tests first, then broader tests if time permits.
- For destructive database verification such as `migrate:fresh --seed`, only run it when the task truly needs it.
