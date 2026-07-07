# SSLCommerz Integration Plan

This plan outlines the integration of the SSLCommerz payment gateway into the PwaPractice application to restrict access to app downloads and content reading.

## Goal

To require a small payment before users can download the PWA and read the questions/content. Users who register but haven't paid will be redirected to a payment page and cannot access the core platform features.

## User Review Required

> [!IMPORTANT]
> **Store ID and Store Password**
> To implement the payment flow, we need the **Store ID** and **Store Password** from your SSLCommerz Sandbox Dashboard (https://sandbox.sslcommerz.com/merchant/). Please ensure you have them ready, as they will be added to the `.env` file once we begin.

> [!WARNING]
> **Access Restriction Strategy**
> Since the goal is to prevent non-paid users from reading questions, we will introduce a middleware (`EnsureUserHasPaid`). This middleware will wrap around the frontend routes (`/category/*`, `/live-exams`, etc.). If a user hasn't paid, they will be redirected to the payment checkout page. Do you agree with this flow?

## Proposed Changes

### Environment Configuration
Add SSLCommerz credentials to the `/.env` file.
- `SSLCOMMERZ_STORE_ID=your_store_id`
- `SSLCOMMERZ_STORE_PASSWORD=your_store_password`
- `SSLCOMMERZ_IS_SANDBOX=true`

---

### Database Models & Migrations
We will create tables to track the payments and update the users table.

#### [NEW] add_is_paid_to_users_table (Migration)
- Add a boolean column `is_paid` (default: false) to quickly check user access without complex queries.

#### [NEW] create_payment_orders_table (Migration)
#### [NEW] create_payment_transactions_table (Migration)
#### [NEW] PaymentOrder.php (Model)
#### [NEW] PaymentTransaction.php (Model)
- **payment_orders**: Tracks what the user is buying (e.g., "App Access"), the amount, and status (pending, paid, failed).
- **payment_transactions**: Stores the raw transaction data, transaction ID, and gateway response from SSLCommerz.

---

### Middleware
To secure the content and PWA.

#### [NEW] EnsureUserHasPaid.php (Middleware)
- `app/Http/Middleware/EnsureUserHasPaid.php`: Checks if `auth()->user()->is_paid` is true. If false, redirects to `/payment/checkout`.
- Will be applied to all study, quiz, and live exam routes in `routes/web.php`.

---

### Services
To isolate the payment gateway logic.

#### [NEW] SslCommerzService.php
- `app/Services/SslCommerzService.php`: Handles API calls to SSLCommerz (initiate payment, validate IPN, check status).

---

### Controllers & Routing
To manage the payment flow and webhooks.

#### [NEW] PaymentController.php
- `app/Http/Controllers/PaymentController.php`: Contains methods for `checkout`, `success`, `fail`, `cancel`, and `ipn` (webhook).

#### [MODIFY] routes/web.php
- Add payment routes (`/payment/checkout`, `/payment/success`, etc.).
- Apply the `EnsureUserHasPaid` middleware to the content routes.

---

### Views
Student-facing views for the payment process.

#### [NEW] resources/views/payments/checkout.blade.php
- A simple page explaining that a small payment is required to unlock the app and read questions, containing a "Pay Now" button.

## Verification Plan

### Automated Tests
- Feature test to ensure `EnsureUserHasPaid` middleware blocks unpaid users.
- Feature test for the payment success webhook (IPN) properly marking the user as `is_paid = true`.

### Manual Verification
1. Register a new user account.
2. Attempt to view a category/question (should redirect to checkout).
3. Proceed with the Sandbox payment using test cards.
4. Verify successful redirection.
5. Confirm the user can now read questions and the `is_paid` flag is updated in the database.
