# Payment Gateway Tests Implementation Plan

The payment gateway feature (checkout, IPN, callbacks, middleware) is critical for business continuity. Currently, no automated tests exist for the `PaymentController` and `SslCommerzService` integration. This plan outlines the creation of a comprehensive test suite to ensure the payment logic is robust and prevents regressions in the future.

## User Review Required

> [!NOTE]
> The test suite will use mock objects for the `SslCommerzService` so it doesn't make real network requests to the payment gateway during testing. This ensures tests run quickly and don't fail due to network issues. 

Please review the proposed test cases below and let me know if you approve.

## Proposed Changes

### Tests

#### [NEW] tests/Feature/PaymentTest.php
This new test file will contain the following test cases:

1. **Checkout Access**:
   - `test_unpaid_user_can_access_checkout_page`: Verifies that unpaid users can view the checkout page.
   - `test_paid_user_cannot_access_checkout_page`: Verifies that paid users are redirected to the home page if they try to access the checkout page.

2. **Middleware Protection**:
   - `test_unpaid_user_cannot_access_paid_routes`: Verifies that an unpaid user gets redirected or blocked when trying to access restricted routes (e.g., `level.questions`).

3. **Payment Initiation**:
   - `test_payment_initiation_redirects_to_gateway`: Mocks the `SslCommerzService`, initiates a payment, and verifies that an internal `PaymentOrder` is created as `pending` and the user is redirected to the mocked gateway URL.

4. **Successful Callbacks**:
   - `test_payment_success_callback_updates_status`: Simulates a successful return from the gateway. Mocks validation to return a valid response, verifies the `is_paid` flag is updated to true, the `PaymentTransaction` is logged, and the user session is restored.
   - `test_ipn_webhook_validates_and_updates_status`: Simulates an IPN hit in the background, verifies database updates without a user session.

5. **Failure & Cancellation**:
   - `test_payment_fail_callback`: Simulates a failed payment callback, verifies order status becomes `failed`, and user remains unpaid.
   - `test_payment_cancel_callback`: Simulates a cancelled payment callback, verifies order status becomes `cancelled`, and user remains unpaid.

## Verification Plan

### Automated Tests
- Run `php artisan test --filter PaymentTest` to ensure all payment integration flows pass successfully.
