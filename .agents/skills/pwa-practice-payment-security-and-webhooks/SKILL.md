---
name: pwa-practice-payment-security-and-webhooks
description: Use when designing or implementing payment security, webhooks, callbacks, reconciliation, or fraud-resistant payment behavior in the PwaPractice repository. Covers signature validation, idempotency, replay protection, server-side verification, and operational safety for payment gateway integration in Laravel.
---

# PwaPractice Payment Security And Webhooks

Use this skill whenever the task touches payment confirmation, webhook handling, or money-sensitive state changes.

## Non-Negotiable Rules

- Never trust the frontend or redirect query alone as proof of payment.
- Always verify payment server-side with the gateway when the provider supports it.
- Validate webhook signatures or hashes before processing.
- Make webhook and callback processing idempotent.
- Never unlock content twice from repeated provider notifications.

## Webhook Design

Preferred flow:

1. Receive webhook
2. Validate signature/authenticity
3. Log the event payload
4. Check idempotency key or provider event ID
5. Verify the payment against internal records
6. Apply a safe state transition
7. Dispatch follow-up jobs if needed

## Idempotency Expectations

Protect against:

- duplicate webhook delivery
- user refreshing callback URLs
- provider retry storms
- out-of-order events

Use unique constraints or processed-event tracking where appropriate.

## Safe State Transitions

- `pending -> paid` is normal after verification.
- `pending -> failed` is normal when the provider confirms failure.
- Ignore or explicitly guard invalid transitions such as:
  - `paid -> pending`
  - `refunded -> paid` without a separate re-payment flow

## Secrets And Configuration

- Keep gateway secrets in `.env`.
- Expose only non-sensitive config through `config/services.php` or a dedicated payment config file.
- Never hardcode API keys, signature secrets, or merchant credentials.

## Logging Rules

- Log enough for audit and debugging.
- Do not log full card data or secret material.
- Mask personal or sensitive fields when persisting provider payloads if needed.

## Fraud And Abuse Considerations

- Validate expected amount and currency against internal order data.
- Validate that the payment belongs to the expected authenticated user or order owner when applicable.
- Reject mismatched references even if the provider says payment succeeded.
- Consider timeout and manual-review paths for ambiguous states.

## Reconciliation

If payment volume grows, support a reconciliation workflow:

- query unsettled or pending transactions
- re-verify with provider
- mark stale records for manual review

This can be implemented with scheduled jobs later.

## Testing Checklist

- valid webhook updates payment once
- duplicate webhook does not duplicate side effects
- invalid signature is rejected
- mismatched amount is rejected
- stale or unknown transaction reference is rejected
- paid status unlocks the correct product exactly once

## Repo Fit

- Put security-sensitive verification logic in services or dedicated handlers, not in Blade or ad hoc controller code.
- If processing becomes heavy, queue it with jobs and keep handlers thin.
- For admin review screens, surface suspicious or failed transactions clearly.
