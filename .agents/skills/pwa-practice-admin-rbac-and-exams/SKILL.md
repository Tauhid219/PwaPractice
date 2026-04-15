---
name: pwa-practice-admin-rbac-and-exams
description: Use when changing admin panel behavior, roles and permissions, user management, question management, or live exam flows in the PwaPractice repository. Covers Spatie permission usage, admin route expectations, and the link between admin CRUD and student-facing exam behavior.
---

# PwaPractice Admin, RBAC, And Exams

Use this skill when the task touches the admin panel, role/permission rules, or live exam management.

## Admin Surface

- Admin routes live under the `admin.` route group in `routes/web.php`.
- Admin controllers live in `app/Http/Controllers/Admin`.
- Admin views live in `resources/views/admin`.
- Protection relies on:
  - `auth`
  - `admin` middleware
  - Spatie `permission:` middleware on sensitive routes

## Permission-Sensitive Areas

- Dashboard access already uses explicit permission checks.
- User, role, and permission management are exposed as admin resources.
- If you add a new admin capability, decide whether it should be:
  - admin-only by middleware
  - permission-gated by Spatie
  - both

## Expected RBAC Workflow

1. Update route or controller authorization rules.
2. Update permission seeding if a new ability is introduced.
3. Ensure admin UI only renders actions the user is allowed to perform.
4. Add tests for allowed and denied paths.

## Files To Inspect For RBAC Changes

- `routes/web.php`
- `app/Http/Controllers/Admin/AdminController.php`
- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Controllers/Admin/RoleController.php`
- `app/Http/Controllers/Admin/PermissionController.php`
- `database/seeders/RolesAndPermissionsSeeder.php`
- `docs/advanced_rbac_plan.md`
- `docs/role_permission_plan.md`

## Live Exam Workflow

- Student live exam flow is in `app/Http/Controllers/Frontend/LiveExamController.php`.
- Admin live exam CRUD and question assignment flow is in `app/Http/Controllers/Admin/LiveExamController.php`.
- Relevant models:
  - `LiveExam`
  - `LiveExamQuestion`
  - `LiveExamAttempt`

## Live Exam Change Checklist

1. Review how the exam is created and scheduled in admin.
2. Review how a student joins, submits, and views results.
3. Keep question assignment rules and attempt recording consistent.
4. Check whether reporting/result pages also need updates.
5. Add tests for submission and result visibility when behavior changes.

## Quality Bar

- Avoid pushing business rules into Blade templates.
- Keep permission checks explicit and easy to audit.
- For list pages and dashboards, watch for N+1 queries and eager load where needed.
- Treat result visibility and exam submission windows as regression-prone areas.
