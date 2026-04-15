---
name: pwa-practice-testing-and-quality
description: Use when adding tests, reviewing regressions, or verifying behavior in the PwaPractice repository. Covers the repo's Laravel testing layout, when to write feature vs unit tests, and what to validate for quiz, progress, RBAC, live exams, and PWA-adjacent changes.
---

# PwaPractice Testing And Quality

Use this skill whenever code changes are made in this repository. Senior-level work here should normally end with targeted verification.

## Test Layout

- Feature tests: `tests/Feature`
- Unit tests: `tests/Unit`
- Shared base: `tests/TestCase.php`

## Current Test Style

- Feature tests are used for route access, database side effects, and end-to-end Laravel behavior.
- Unit tests are used for isolated domain logic such as quiz scoring.
- `RefreshDatabase` is appropriate for most behavior-changing backend tests here.

## Test Selection Guide

- Write a unit test when:
  - the logic is pure or nearly pure
  - the code lives in a service or helper-like class
  - database and HTTP are not the main concern
- Write a feature test when:
  - route access matters
  - middleware matters
  - validation matters
  - database writes or reads matter
  - permissions or auth matter

## High-Risk Areas That Deserve Tests

- Quiz scoring, pass/fail thresholds, and attempt recording
- Level unlock and progress update logic
- Read-question sync behavior
- Role and permission enforcement
- Admin CRUD authorization
- Live exam join, submit, and result visibility
- Any caching-related change that can affect frontend data freshness

## Recommended Commands

- Full test suite: `php artisan test`
- If you only changed one narrow area, run the focused test file first.
- Use formatting or linting tools when relevant, but test behavior is the priority.

## Review Mindset

- Look for behavioral regressions first, then code style.
- Confirm both success and denial cases for auth and permissions.
- If data shape changed, test the new happy path and at least one edge case.
- If you could not verify a risky path, call that out explicitly.
