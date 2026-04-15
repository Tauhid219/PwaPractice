---
name: pwa-practice-frontend-pwa-workflow
description: Use when changing student-facing UI, admin-facing Blade templates, install or offline behavior, manifest and service worker logic, or responsive frontend flows in the PwaPractice repository. Covers the split frontend stack, Bangla-first UX, and PWA-specific safety checks.
---

# PwaPractice Frontend And PWA Workflow

Use this skill for Blade, CSS, JS, and PWA behavior changes in this repository.

## UI Split

- Student/public pages live under `resources/views/frontend`.
- Admin pages live under `resources/views/admin`.
- Frontend assets are a mix of:
  - Vite-managed `resources/css` and `resources/js`
  - Public theme assets under `public/frontend`
  - AdminLTE assets under `public/vendor/adminlte`

Do not assume the same design language or layout structure applies to both surfaces.

## UX Expectations

- Preserve Bangla-first copy where it exists.
- Keep mobile behavior solid; this app is learning-focused and PWA-oriented.
- For student pages, favor clarity and fast task completion over ornamental UI changes.
- For admin pages, keep management workflows predictable and information-dense.

## PWA Files

- `public/manifest.json`
- `public/sw.js`
- offline route in `routes/web.php`
- offline view: inspect `resources/views/offline.blade.php` or other offline-related templates if present
- planning doc: `docs/pwa_plan.md`

## PWA Safety Rules

- Be careful with cache key/version changes in `sw.js`.
- Keep install/offline behavior deterministic.
- If route structure changes, consider whether offline caching logic or fallback behavior must also change.
- Avoid caching behavior that could expose authenticated or personalized data incorrectly.

## Frontend Change Workflow

1. Inspect the controller that provides the view data.
2. Inspect the Blade template and layout it extends.
3. Inspect any asset or JS file that supports the interaction.
4. Verify responsive behavior and copy consistency.
5. If the page participates in offline behavior, inspect `public/sw.js`.

## Student-Facing Hotspots

- Category and level browsing
- Question reading and mark-as-read behavior
- Quiz start, submit, and result pages
- Live exam listing, join flow, taking page, and results
- Profile and progress pages

## Admin-Facing Hotspots

- Dashboard summaries
- User, role, and permission CRUD pages
- Category, question, and live exam management pages

## Verification

- For UI changes, verify the exact page route manually if possible.
- For PWA changes, verify install, refresh, and offline fallback behavior, not just rendered HTML.
