---
name: pwa-practice-payment-data-modeling
description: Use when designing payment-related tables, models, relationships, and state fields for the PwaPractice repository. Covers Laravel-friendly schema design for orders, transactions, webhook logs, refunds, reconciliation support, and how to model payment lifecycle data without coupling the app too tightly to one gateway.
---

# PwaPractice Payment Data Modeling

Use this skill before writing migrations for payment features in this repository.

## Main Goal

Design a payment schema that is:

- easy to query
- safe for audits
- compatible with retries and webhooks
- flexible enough for multiple gateways

## Preferred Core Entities

Start with clear separation between these concepts:

- purchasable business object
  - premium quiz pack
  - live exam registration
  - subscription plan
- internal order
- payment transaction
- provider event or webhook log
- optional refund record

Do not collapse all of this into one table unless the payment use case stays extremely small.

## Recommended Table Shape

Typical tables to consider:

- `payment_orders`
- `payment_transactions`
- `payment_webhook_events`
- `payment_refunds`

Depending on scope, you may also need:

- `payment_methods`
- `subscriptions`
- `invoice_items`

## `payment_orders` Responsibilities

Use the order layer for internal business meaning:

- user association
- product or feature being purchased
- expected amount and currency
- order status
- fulfilment state
- internal reference

This table answers: what was the user trying to buy?

## `payment_transactions` Responsibilities

Use the transaction layer for gateway-specific payment attempts:

- order relationship
- provider name
- provider transaction/reference ID
- amount and currency seen by provider
- transaction status
- raw payload snapshot
- verification timestamp
- failure reason if known

This table answers: what did the gateway say happened?

## `payment_webhook_events` Responsibilities

Use webhook event storage for:

- provider name
- provider event ID if available
- related internal order or transaction reference
- signature validation result
- payload snapshot
- processed timestamp
- processing outcome

This table helps with:

- idempotency
- troubleshooting
- replay analysis

## Status Design

Keep business and payment statuses separate when needed.

Examples:

- order status:
  - `draft`
  - `pending_payment`
  - `paid`
  - `cancelled`
  - `fulfilled`
  - `refunded`
- transaction status:
  - `initiated`
  - `pending`
  - `authorized`
  - `paid`
  - `failed`
  - `cancelled`
  - `refunded`

Avoid one overloaded `status` field if it mixes fulfilment and gateway state.

## Reference Design

- Use internal references that are unique and safe to expose when needed.
- Store provider references separately.
- Add unique indexes for:
  - internal order reference
  - provider transaction reference where applicable
  - provider webhook event ID where applicable

## Relationship Guidance

- `User` has many payment orders
- payment order has many payment transactions
- payment order may have many refunds
- payment transaction may have many webhook events if events map best at transaction level

Choose one relationship strategy and keep it consistent.

## JSON And Audit Data

- Store raw provider payloads in JSON columns when useful.
- Keep normalized columns for important query fields like status, amount, and reference.
- Do not rely on raw JSON alone for reporting-critical data.

## Migration Checklist

1. Add explicit indexes for internal and provider references.
2. Make amounts precise and consistent.
3. Store currency explicitly.
4. Include nullable audit timestamps like `verified_at` or `processed_at`.
5. Plan for refunds even if refunds are phase two.

## Repo Fit

- Put migrations under `database/migrations`.
- Put Eloquent models under `app/Models`.
- If order fulfilment unlocks features like exams or premium content, keep that linkage explicit in schema or service logic.
- Update factories and tests after introducing payment tables.
