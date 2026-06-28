# PwaPractice Code Audit And Remediation Plan

Date: 2026-06-27

## Scope

This report captures the code audit findings for the Laravel-based quiz PWA application and a proposed remediation plan. The review focused on:

- Security
- Scalability and performance
- PWA offline reliability
- Code architecture and operational safety

## Executive Summary

The application has a solid Laravel baseline in a few areas: it uses Blade escaping by default, request validation exists in several flows, CSRF protection is present on reviewed form submissions, and route-level authorization for admin surfaces is generally in place. However, there are several important weaknesses that need attention.

The most serious issue is that the normal quiz flow exposes correct answers directly in the rendered HTML and browser-side JavaScript. There is also a race condition in live exam submission handling that can allow duplicate attempts under concurrency. Offline support is currently limited to page fallback and static asset caching; it does not securely preserve in-progress quiz or exam submissions for later sync. Cache invalidation is also too broad and will not scale well under real traffic.

## Detailed Findings

### 1. Critical: Quiz Correct Answers Are Exposed In The Browser

Severity: Critical

Files:

- [resources/views/frontend/quiz/taking.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/quiz/taking.blade.php:32)
- [resources/views/frontend/quiz/taking.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/quiz/taking.blade.php:35)
- [resources/views/frontend/quiz/taking.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/quiz/taking.blade.php:130)
- [resources/views/frontend/quiz/taking.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/quiz/taking.blade.php:160)

What is happening:

- The view builds a `$correctString` from `correct_answers` or `answer_text`.
- That value is rendered into `data-correct` on each question container.
- Frontend JavaScript reads `data-correct`, compares the typed answer in the browser, and immediately determines correctness.

Why this is a problem:

- Any user can inspect the DOM and see the answer before answering.
- A user can also script the page and auto-read all answers.
- This defeats the core trust boundary of the quiz flow.

Impact:

- Quiz integrity is broken.
- Any score produced by the current client-side quiz interaction is untrustworthy.

### 2. High: Live Exam Submission Is Not Race-Safe

Severity: High

Files:

- [app/Http/Controllers/Frontend/LiveExamController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Frontend/LiveExamController.php:57)
- [app/Jobs/ProcessLiveExamScore.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Jobs/ProcessLiveExamScore.php:50)
- [database/migrations/2026_03_11_081209_create_live_exam_attempts_table.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/database/migrations/2026_03_11_081209_create_live_exam_attempts_table.php:14)

What is happening:

- The controller checks whether an attempt already exists using `exists()`.
- If not, it dispatches a job.
- The job later creates the attempt record.
- The table does not enforce uniqueness for `(live_exam_id, user_id)`.

Why this is a problem:

- Two near-simultaneous submissions can pass the `exists()` check before either job writes to the database.
- This can create duplicate attempts for one user and one exam.

Impact:

- Leaderboard integrity can be corrupted.
- Retry behavior, auto-submit, and manual submit can collide.

### 3. High: Anti-Cheat Data Is Client-Trusted And Easily Tampered

Severity: High

Files:

- [resources/views/frontend/live_exam/taking.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/taking.blade.php:34)
- [resources/views/frontend/live_exam/taking.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/taking.blade.php:311)
- [app/Http/Controllers/Frontend/LiveExamController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Frontend/LiveExamController.php:62)
- [app/Http/Requests/SubmitLiveExamRequest.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Requests/SubmitLiveExamRequest.php:25)

What is happening:

- `tab_switches` is stored in a hidden field.
- JavaScript increments it on `visibilitychange`.
- The controller passes it straight into scoring job dispatch.
- The request validator does not validate `tab_switches`.

Why this is a problem:

- The browser fully controls this number.
- A user can set it to `0` from DevTools before submitting.

Impact:

- Anti-cheat reporting is not reliable.
- Admin decisions based on this signal can be wrong.

### 4. High: Offline Quiz And Exam Recovery Does Not Exist

Severity: High

Files:

- [resources/views/frontend/live_exam/taking.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/live_exam/taking.blade.php:287)
- [public/sw.js](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/public/sw.js:95)
- [resources/views/frontend/questions.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/questions.blade.php:152)

What is happening:

- The service worker only handles static caching and offline page fallback.
- Quiz or exam answers are not stored in IndexedDB.
- There is no signed offline submission queue.
- There is no sync job or retry protocol for answer payloads.
- The app explicitly warns that progress will not be saved.

Why this is a problem:

- If the internet drops during an exam, answers can be lost.
- There is no trustworthy offline-to-online reconciliation mechanism.

Impact:

- Poor user reliability during real mobile usage.
- No secure offline exam recovery.

### 5. High: Cache Invalidation Strategy Is Too Broad

Severity: High

Files:

- [app/Models/Question.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Models/Question.php:21)
- [app/Models/Category.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Models/Category.php:17)
- [app/Models/Level.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Models/Level.php:21)

What is happening:

- On save/delete, these models call `Cache::flush()`.

Why this is a problem:

- One content edit invalidates the entire app cache.
- This is expensive under load.
- It can create avoidable cache stampedes.

Impact:

- Lower performance during admin operations.
- Poor cache efficiency as traffic grows.

### 6. Medium: Quiz Query Pattern Will Become Expensive At Scale

Severity: Medium

Files:

- [app/Http/Controllers/Frontend/QuizController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Frontend/QuizController.php:28)
- [app/Http/Controllers/Frontend/QuizController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Frontend/QuizController.php:49)
- [app/Http/Middleware/CheckLevelAccess.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Middleware/CheckLevelAccess.php:48)

What is happening:

- Quiz start uses `inRandomOrder()->get()`.
- Quiz submit refetches the full question set.
- The level access middleware can issue repeated question lookups.

Why this is a problem:

- Random sorting on large tables is expensive.
- Repeated full-set fetches multiply DB pressure.

Impact:

- Slower response time under concurrent quiz traffic.

### 7. Medium: Security Headers Are Incomplete For A Modern PWA

Severity: Medium

Files:

- [app/Http/Middleware/SecurityHeaders.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Middleware/SecurityHeaders.php:20)
- [resources/views/frontend/layouts/master.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/layouts/master.blade.php:22)
- [resources/views/frontend/layouts/master.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/layouts/master.blade.php:103)
- [resources/views/frontend/layouts/master.blade.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/resources/views/frontend/layouts/master.blade.php:467)

What is happening:

- Basic headers like `nosniff` and `X-Frame-Options` are present.
- There is no Content Security Policy.
- There is no HSTS policy visible in app middleware.
- Third-party assets are pulled from external CDNs and GitHub raw URLs.

Why this is a problem:

- PWA and frontend execution surface remains broader than necessary.
- External assets increase supply-chain risk.

Impact:

- Weaker XSS hardening and resource-control posture than recommended.

### 8. Medium: Admin Controllers Mix Validation, Business Rules, Persistence, And Error Exposure

Severity: Medium

Files:

- [app/Http/Controllers/Admin/LiveExamController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/LiveExamController.php:43)
- [app/Http/Controllers/Admin/LiveExamController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/LiveExamController.php:267)
- [app/Http/Controllers/Admin/QuestionController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/QuestionController.php:163)

What is happening:

- Large controllers hold validation, orchestration, and persistence logic.
- Import exception messages and traces are logged directly.
- Raw exception messages are flashed back to the user.

Why this is a problem:

- Harder to test and maintain.
- Risk of leaking sensitive internals through user-visible errors or logs.

Impact:

- Higher long-term maintenance cost.
- Poorer operational safety.

### 9. Medium: Question Data Integrity Rules Are Inconsistent

Severity: Medium

Files:

- [app/Http/Requests/StoreQuestionRequest.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Requests/StoreQuestionRequest.php:25)
- [app/Http/Requests/UpdateQuestionRequest.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Requests/UpdateQuestionRequest.php:25)
- [app/Http/Controllers/Admin/QuestionController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/QuestionController.php:84)
- [app/Http/Controllers/Admin/LiveExamController.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Http/Controllers/Admin/LiveExamController.php:145)

What is happening:

- Normal question store/update requests allow nullable options.
- The normal question flow does not enforce that `answer_text` matches one of the options.
- The live exam question flow does enforce that rule.

Why this is a problem:

- It is possible to store malformed quiz questions.
- Validation behavior differs across two question management paths.

Impact:

- Data inconsistency in the question bank.

### 10. Low: Future Mass Assignment Risk Exists On User Model

Severity: Low

Files:

- [app/Models/User.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/app/Models/User.php:23)

What is happening:

- `is_admin`, `current_streak`, and `last_quiz_date` are fillable.

Why this is a problem:

- Current reviewed flows do not abuse this.
- But it creates a future footgun if a broad `$user->fill(...)` or `User::create($request->all())` path is introduced later.

Impact:

- Latent privilege or integrity risk in future changes.

## Areas That Look Reasonably Good

- No raw SQL or string-built query patterns were found in the reviewed paths, so direct SQL injection risk appears low.
- CSRF protection is present in normal reviewed form submissions and AJAX mark-read sync.
- Admin permission middleware usage is broadly in the right direction.
- There is existing throttling for quiz and live exam submission flows.

## Missing Or Weak Test Coverage

Files reviewed:

- [tests/Feature/QuizTest.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/tests/Feature/QuizTest.php:45)
- [tests/Feature/LiveExamTest.php](/C:/xampp/htdocs/Laravel-Practice/PwaPractice/tests/Feature/LiveExamTest.php:45)

Observed gaps:

- No test that prevents answer leakage.
- No test for duplicate live exam submission under concurrency.
- No test for anti-cheat tampering.
- No test for service worker or offline sync behavior.
- No test for cache invalidation correctness.

## Proposed Remediation Plan

### Phase 1: Fix Integrity And Security-Critical Issues First

Priority: Immediate

1. Remove all correct answers from normal quiz HTML and JavaScript.
2. Move answer verification fully to the server.
3. Change quiz UX so the client only collects answers and submits them, or verifies one question at a time through a signed backend endpoint.
4. Add a database unique index on `live_exam_attempts (live_exam_id, user_id)`.
5. Wrap live exam submission creation in an idempotent server-side flow.
6. Validate `tab_switches` as an integer with sane bounds, but stop treating it as a trusted anti-cheat metric.
7. Ensure live exam submit rejects requests after exam end on the server side, not only in the join page.

### Phase 2: Improve PWA Reliability And Offline Safety

Priority: High

1. Define whether offline support is allowed for:
   - study mode only
   - quiz mode
   - live exams
2. For study mode:
   - keep static/document caching
   - optionally cache read-only question content safely
3. For quiz mode:
   - store in-progress answers locally in IndexedDB
   - sign a quiz session on the server
   - submit answers only for a valid session token
4. For sync:
   - create an offline submission queue in IndexedDB
   - retry on reconnect
   - deduplicate on the server using idempotency keys
5. For live exams:
   - do not rely on local scores
   - only persist raw selected answers plus server-issued session metadata
   - reject stale or replayed syncs on the server
6. For iOS:
   - assume Background Sync is unavailable or unreliable
   - implement explicit reconnect detection and manual retry UI
   - show a clear banner that queued sync will happen only when the app is reopened online

### Phase 3: Improve Performance And Scalability

Priority: High

1. Replace broad `Cache::flush()` with targeted cache keys or tags.
2. Cache category and level data with explicit invalidation keys.
3. Cache question ID pools instead of full rendered answer-bearing payloads.
4. Avoid `inRandomOrder()` on large question tables:
   - preselect randomized IDs
   - or use a shuffled ID pool strategy
5. Reduce repeated question queries in `CheckLevelAccess`.
6. Review additional indexes for:
   - `live_exam_attempts (live_exam_id, user_id)`
   - `live_exam_questions (live_exam_id, question_id)`
   - leaderboard access paths if scoreboards grow large

### Phase 4: Strengthen Web Security Hardening

Priority: Medium

1. Add a Content Security Policy.
2. Minimize external asset origins.
3. Prefer self-hosted audio and frontend assets over GitHub raw URLs.
4. Add HSTS in production behind HTTPS.
5. Consider stricter `frame-ancestors` and `base-uri` controls.
6. Review service worker scope and cached responses to ensure authenticated data is never cached unintentionally.

### Phase 5: Clean Up Architecture

Priority: Medium

1. Introduce dedicated Form Requests for admin live exam create/update and question management flows.
2. Move scoring, attempt creation, and exam submission rules into service classes.
3. Centralize exception handling for import flows.
4. Replace raw flashed exception messages with generic user-safe messages.
5. Log structured error context without dumping sensitive internals to end users.
6. Tighten model fillable definitions where fields should only be server-managed.

## Suggested Concrete Deliverables

Recommended implementation order:

1. Secure quiz answer flow redesign
2. Live exam submission idempotency and unique DB constraint
3. Targeted cache invalidation refactor
4. Offline queue design for study and quiz flows
5. CSP and third-party asset cleanup
6. Controller-to-service refactor and improved error handling
7. Feature tests for the above

## Recommended New Tests

1. Feature test proving quiz HTML does not contain correct answers.
2. Feature test proving duplicate live exam submit cannot create two attempts.
3. Feature test proving live exam submit after end time is rejected server-side.
4. Feature test proving unauthorized attempt result access is denied.
5. Cache invalidation tests for category, level, and question updates.
6. Integration tests for offline queue payload acceptance and replay rejection once the sync flow exists.

## Conclusion

The project is on a workable Laravel foundation, but the current quiz trust model and live exam submission model need to be tightened before the application can be considered secure and reliable for real competitive use. The most urgent work is to remove client-side answer exposure, make live exam submissions idempotent at the database level, and define a proper offline sync protocol that does not trust local scoring or mutable browser state.
