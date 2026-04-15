---
name: pwa-practice-payment-operations-and-reporting
description: Use when planning admin payment visibility, reconciliation workflows, refund handling, reporting, or operational support for payments in the PwaPractice repository. Covers the practical back-office needs around transaction monitoring, failed payments, pending verification, refunds, and finance-friendly reporting structure.
---

# PwaPractice Payment Operations And Reporting

Use this skill when payment work needs an admin or support workflow, not just checkout.

## Operational Goal

Make payments supportable after launch.

That means the team should be able to answer:

- which payments succeeded
- which payments failed
- which payments are stuck in pending state
- whether a user actually received access
- whether a refund was issued

## Minimum Admin Visibility

If payments are introduced, plan for admin access to:

- payment order list
- transaction list
- failed transaction list
- pending verification list
- refund history
- detail page for one payment order with linked transactions/events

## Useful Admin Fields

Expose searchable fields like:

- internal order reference
- provider name
- provider transaction ID
- user
- amount
- currency
- payment status
- fulfilment status
- created at
- verified at

## Reconciliation Workflow

Design for a periodic process that can review:

- old pending payments
- webhook-missed transactions
- mismatched paid-vs-unlocked states
- suspicious failures

This can later run through scheduled commands or queued jobs.

## Refund Workflow

Even if refunds are not supported on day one, design for them.

Track:

- refund request reason
- refunded amount
- refund status
- provider refund reference
- who approved or processed it
- timestamps

Do not overwrite the original payment transaction to represent a refund; record it explicitly.

## Reporting Mindset

Prefer normalized fields that allow:

- total revenue by date range
- revenue by gateway
- failed payment count
- refund totals
- payment conversion reporting if product flow supports it

If finance-style reporting is likely, avoid burying important numbers only in JSON payloads.

## Support Workflow

Admin/support should be able to investigate:

1. what the user tried to buy
2. whether the gateway says it was paid
3. whether the app verified it
4. whether the app granted access
5. what failed if it did not complete

## Repo Fit

- Admin routes can live in the existing `admin.` route group.
- Admin pages should live under `resources/views/admin`.
- Keep reporting queries efficient; use eager loading and indexed filters.
- If operational rules become complex, move them into services instead of bloating controllers.

## Verification Checklist

- admin can find a transaction by internal reference
- admin can find a transaction by provider reference
- pending and failed views show correct records
- refund records remain linked to the original order
- reporting totals use verified payment records, not unverified redirects
