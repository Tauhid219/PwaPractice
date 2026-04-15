---
name: pwa-practice-payment-gateway-integration
description: Use when adding a payment gateway to the PwaPractice repository. Covers Laravel payment integration workflow for checkout, order and transaction modeling, provider abstraction, callback handling, status transitions, and how payment features should fit this quiz platform's existing routes, jobs, and admin flows.
---

# PwaPractice Payment Gateway Integration

Use this skill when implementing payment features in this repository for the first time or when extending an existing payment flow.

## Goal

Build payment support in a way that is:

- provider-agnostic
- safe to retry
- easy to test
- compatible with Laravel queues, jobs, and admin reporting

## Default Architecture

Prefer this shape:

1. Payment domain models and migrations
2. Provider abstraction in `app/Services` or a dedicated payment namespace
3. Checkout initiation endpoint
4. Callback or webhook endpoint
5. Background job processing for non-trivial verification or reconciliation
6. Admin visibility for transaction status and failures

## Suggested Domain Objects

If payment is added, expect to introduce concepts like:

- `Order` or `PaymentOrder`
- `PaymentTransaction`
- optional `PaymentAttempt`
- optional `WebhookEventLog`

Keep provider transaction IDs separate from internal IDs.

## Integration Workflow

1. Define the business reason for payment:
   - course unlock
   - premium quiz pack
   - live exam registration
   - subscription
2. Design internal payment states before writing provider code.
3. Add migrations and models for payment tracking.
4. Create a provider interface so gateway-specific logic stays isolated.
5. Implement checkout/start-payment flow.
6. Implement callback and webhook verification flow.
7. Update user-facing success, failure, and pending pages.
8. Add admin-side visibility and test coverage.

## State Management

Do not treat redirect success as proof of payment.

Use explicit internal states such as:

- `initiated`
- `pending`
- `paid`
- `failed`
- `cancelled`
- `refunded`

Only unlock paid features after server-side verification.

## Repo Fit

- Use `routes/web.php` and, if needed later, dedicated payment routes grouped by auth and middleware.
- Put reusable payment logic in `app/Services`.
- If provider confirmation is slow or multi-step, hand off follow-up work to jobs under `app/Jobs`.
- Keep Blade pages separate for student-facing purchase flow and admin-facing review flow.

## Data Design Expectations

- Store raw provider payloads carefully for audit/debugging.
- Track:
  - internal reference
  - provider reference
  - amount
  - currency
  - status
  - payer identity when relevant
  - verified timestamp
- Make transaction reference fields unique where appropriate.

## Gateway Abstraction Rule

Do not spread provider SDK calls across controllers.

Prefer:

- controller for orchestration
- service for gateway integration
- model for persistence
- job for retries or asynchronous verification

## Admin Expectations

If payments are introduced, admin should eventually be able to review:

- transaction list
- failed payments
- pending verifications
- refunded records if supported

## Verification

- Add feature tests for start, callback, and failure paths.
- Add unit tests for status transition rules if they become non-trivial.
- Test duplicate callback/webhook behavior before considering the feature safe.
