# 🛠️ PwaPractice — Professional Upgrade Plan

> **উদ্দেশ্য:** Payment gateway এর আগ পর্যন্ত প্রজেক্টকে আরো robust, secure, clean এবং professional করে তোলা।
> **নিয়ম:** প্রতিটি phase শেষ হলে marker আপডেট হবে। পরবর্তী phase শুরু হবে user confirmation পাওয়ার পর।

---

## 📊 Phase Status Overview

| Phase | বিষয় | Status |
|-------|-------|--------|
| Phase 01 | Security Headers & HTTPS Enforcement | ✅ Completed |
| Phase 02 | Rate Limiting (Exam & Auth Routes) | ✅ Completed |
| Phase 03 | Anti-Cheat: Tab Switch Detection | ✅ Completed |
| Phase 04 | Toast Notifications (SweetAlert2) | ✅ Completed |
| Phase 05 | Loading Skeleton & Page Transitions | ✅ Completed |
| Phase 06 | Student Frontend Dark Mode | ⏭️ Skipped |
| Phase 07 | Exam Timer — Web Worker Upgrade | ✅ Completed |
| Phase 08 | Online/Offline Status Banner | ✅ Completed |
| Phase 09 | PWA Manifest & Icon Polish | ✅ Completed |
| Phase 10 | N+1 Query Audit & Eager Loading Fix | ✅ Completed |
| Phase 11 | Admin Analytics Dashboard | ✅ Completed |
| Phase 12 | Student Streak & Gamification | ✅ Completed |
| Phase 13 | Test Coverage Expansion | ✅ Completed |
| Phase 14 | Laravel Telescope (Dev Observability) | ✅ Completed |
| Phase 15 | Code Quality & PSR-12 Final Pass | ✅ Completed |

---

## 🔒 Phase 01 — Security Headers & HTTPS Enforcement

**Status:** ✅ Completed
**ক্যাটাগরি:** Security
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- `app/Http/Middleware/SecurityHeaders.php` নামে একটি নতুন middleware তৈরি করা হবে।
- এই middleware নিচের HTTP security headers যোগ করবে:
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy: geolocation=(), microphone=()`
- `bootstrap/app.php`-এ middleware globally register করা হবে।
- `.env`-এ `APP_URL` https দিয়ে configured আছে কিনা verify করা হবে।
- Insecure mixed-content কোনো external CDN link আছে কিনা Blade files-এ scan করা হবে এবং `https://` নিশ্চিত করা হবে।

### টেস্টিং
- Browser DevTools → Network → Response Headers চেক করে headers verify করা হবে।

---

## 🚦 Phase 02 — Rate Limiting (Exam & Auth Routes)

**Status:** ✅ Completed
**ক্যাটাগরি:** Security
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- `AppServiceProvider` বা `bootstrap/app.php`-এ custom rate limiters define করা হবে:
  - **`exam-submit`:** প্রতি user, প্রতি minute-এ সর্বোচ্চ ১ বার exam submit করা যাবে।
  - **`api`:** প্রতি IP, প্রতি minute-এ সর্বোচ্চ ৬০টি API request।
- `routes/web.php`-এ exam submit route-এ `throttle:exam-submit` middleware যোগ করা হবে।
- Rate limit exceed হলে user-friendly বাংলা error message দেখানো হবে।

### টেস্টিং
- Rapid form submission দিয়ে 429 Too Many Requests response verify করা হবে।

---

## 🕵️ Phase 03 — Anti-Cheat: Tab Switch Detection

**Status:** ✅ Completed
**ক্যাটাগরি:** UX / Exam Integrity
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- Live Exam-এর `taking.blade.php`-এ JavaScript যুক্ত করা হবে।
- `document.addEventListener('visibilitychange', ...)` ব্যবহার করে tab switch detect করা হবে।
- প্রতিবার tab switch হলে একটি counter বাড়বে এবং user-কে warning দেওয়া হবে।
- ৩ বারের বেশি switch করলে exam automatically submit হয়ে যাবে এবং note হিসেবে `LiveExamAttempt`-এ `tab_switches` কলাম সেভ হবে।
- Admin panel-এ result দেখার সময় tab switch count দেখানো হবে।

### Database পরিবর্তন
- `live_exam_attempts` টেবিলে `tab_switches` (integer, default 0) কলাম যোগ করা হবে নতুন migration-এর মাধ্যমে।

### টেস্টিং
- Exam চলাকালীন অন্য tab-এ গেলে warning আসছে কিনা verify করা হবে।
- ৩ বার switch করলে auto-submit হচ্ছে কিনা verify করা হবে।

---

## 🔔 Phase 04 — Toast Notifications (SweetAlert2)

**Status:** ✅ Completed
**ক্যাটাগরি:** UX Polish
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- `SweetAlert2`-কে CDN-এর মাধ্যমে উভয় layout-এ (frontend master + admin) যোগ করা হবে।
- বর্তমানে Bootstrap-এর basic `alert` div দিয়ে flash message দেখানো হচ্ছে — এটি পুরোপুরি replace করা হবে।
- একটি reusable Blade partial `_flash_messages.blade.php` তৈরি করা হবে যা SweetAlert2 Toast দিয়ে সব success/error/warning message দেখাবে।
- Quiz result পেজে pass/fail হলে animated SweetAlert2 popup দেখানো হবে।
- Admin CRUD operations (create, update, delete)-এ confirmation dialog যোগ করা হবে।

### টেস্টিং
- Category create, question delete, quiz pass/fail — প্রতিটিতে সুন্দর toast দেখা যাচ্ছে কিনা verify করা হবে।

---

## ⏳ Phase 05 — Loading Skeleton & Page Transitions

**Status:** ✅ Completed
**ক্যাটাগরি:** UX Polish
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- `NProgress.js` CDN থেকে যোগ করা হবে।
- Frontend layout-এ page navigation শুরু হলে NProgress bar দেখাবে, page load শেষে hide হবে।
- Quiz `taking.blade.php`-এ question load হওয়ার সময় একটি pulse skeleton placeholder দেখানো হবে।
- CSS-এ `@keyframes shimmer` animation তৈরি করা হবে skeleton effect-এর জন্য।

### টেস্টিং
- ধীর নেটওয়ার্কে (Chrome DevTools → Slow 3G) page load করে skeleton দেখা যাচ্ছে কিনা verify করা হবে।

---

## 🌙 Phase 06 — Student Frontend Dark Mode

**Status:** ⏭️ Skipped
**কারণ:** User request অনুযায়ী এই phase বাদ দেওয়া হয়েছে।

---

## ⏱️ Phase 07 — Exam Timer: Web Worker Upgrade

**Status:** ✅ Completed
**ক্যাটাগরি:** Performance / Reliability
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- বর্তমান `setInterval`-based timer একটি Web Worker-এ নিয়ে যাওয়া হবে।
- `public/timer-worker.js` নামে নতুন file তৈরি করা হবে।
- Web Worker main thread-এ message পাঠাবে, main thread UI update করবে।
- এর ফলে tab hide থাকলে বা browser throttle করলেও timer accurate থাকবে।
- Timer শেষ হলে worker নিজেই `autoSubmit` signal পাঠাবে।

### টেস্টিং
- Phone-এ অন্য app-এ গিয়ে ফিরে এলে timer correct time দেখাচ্ছে কিনা verify করা হবে।

---

## 📡 Phase 08 — Online/Offline Status Banner

**Status:** ✅ Completed
**ক্যাটাগরি:** PWA / UX
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- Frontend layout-এ একটি fixed top banner যোগ করা হবে।
- `navigator.onLine` এবং `window.addEventListener('offline'/'online', ...)` ব্যবহার করা হবে।
- Internet বন্ধ হলে: লাল banner "⚠️ ইন্টারনেট সংযোগ নেই" দেখাবে।
- Internet ফিরে এলে: সবুজ banner "✅ সংযোগ পুনরায় স্থাপিত হয়েছে" দেখাবে এবং ৩ সেকেন্ড পরে auto-hide হবে।
- CSS transition দিয়ে smooth slide-in/slide-out animation যোগ করা হবে।

### টেস্টিং
- Chrome DevTools-এ Network throttle করে offline করলে banner দেখা যাচ্ছে কিনা verify করা হবে।

---

## 📱 Phase 09 — PWA Manifest & Icon Polish

**Status:** ✅ Completed
**ক্যাটাগরি:** PWA Quality
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হবে
- `public/manifest.json` audit করা হবে। নিচের fields নিশ্চিত করা হবে:
  - `screenshots` array (Lighthouse requirements আছে)
  - `shortcuts` array (quick actions: Home, Live Exam)
  - `categories: ["education"]`
  - `display_override: ["window-controls-overlay"]`
- Multiple icon sizes verify করা হবে (192, 384, 512px)।
- `maskable` icon নিশ্চিত করা হবে Android adaptive icon support-এর জন্য।
- Lighthouse PWA score run করে gaps identify করা হবে।

### টেস্টিং
- Chrome-এ Install PWA করে icon ও splash screen চেক করা হবে।
- Lighthouse → PWA audit চালানো হবে।

---

## 🔍 Phase 10 — N+1 Query Audit & Eager Loading Fix

**Status:** ✅ Completed
**ক্যাটাগরি:** Performance
**সম্পন্ন হওয়ার তারিখ:** ২০ এপ্রিল ২০২৬

### কী করা হবে
- Laravel Debugbar দিয়ে নিচের pages-এর query count audit করা হবে:
  - Admin: User Management (`/admin/users`)
  - Admin: Question List (`/admin/questions`)
  - Frontend: Category listing
  - Frontend: Quiz result page
  - Frontend: Progress dashboard
- Identified N+1 queries-এ `with()` eager loading যোগ করা হবে।
- Admin User list-এ roles relationship properly eager load করা হবে।
- QuizAttempt result-এ level ও category relationship eager load নিশ্চিত করা হবে।

### টেস্টিং
- Debugbar-এ query count before/after compare করা হবে।

---

## 📊 Phase 11 — Admin Analytics Dashboard

**Status:** ✅ Completed
**ক্যাটাগরি:** Feature Addition
**সম্পন্ন হওয়ার তারিখ:** ১৯ এপ্রিল ২০২৬

### কী করা হয়েছে
- `AdminController@index` আপডেট করে detailed analytics logic যোগ করা হয়েছে।
- ৪টি summary info box যোগ করা হয়েছে: Total Students, Questions, Attempts, এবং Pass Rate।
- Chart.js ইন্টিগ্রেট করে ৩টি ডাইনামিক চার্ট যোগ করা হয়েছে:
  - **Activity Last 7 Days** (Bar Chart for Quizzes and Live Exams)
  - **By Category** (Doughnut Chart for Question distribution)
  - **By Difficulty** (Doughnut Chart for Level distribution)
- **Recent Activity Tables** যোগ করা হয়েছে যাতে সর্বশেষ Quiz এবং Live Exam attempts দেখা যায়।

### টেস্টিং
- Admin Dashboard লোড করে চার্টগুলো সঠিক data দেখাচ্ছে কিনা verify করা হয়েছে।

---

## 🏆 Phase 12 — Student Streak & Gamification

**Status:** ✅ Completed
**ক্যাটাগরি:** Feature Addition / Engagement
**সম্পন্ন হওয়ার তারিখ:** ২০ এপ্রিল ২০২৬

### কী করা হয়েছে
- `users` টেবিলে `current_streak` এবং `last_quiz_date` কলাম যুক্ত করা হয়েছে।
- `User` মডেলে `updateStreak()` মেথড তৈরি করা হয়েছে যা প্রতিদিনের প্রথম সফল কুইজে স্ট্রিক বৃদ্ধি করে এবং নিয়মিত না থাকলে তা রিসেট করে।
- `QuizController::submit`-এ কুইজ পাস করার পর স্ট্রিক আপডেট লজিক ইন্টিগ্রেট করা হয়েছে।
- **UI Improvements:**
    - স্টুডেন্ট প্রগ্রেস পেজে "🔥 ধারা (Streak)" কার্ড এবং "সাফল্যের ব্যাজ" (৭ দিন, ৩০ দিন, ১০০ কুইজ) সেকশন যুক্ত করা হয়েছে।
    - মোবাইল বটম নেভিগেশন এবং ডেক্সটপ নেভিবারে স্ট্রিক ইন্ডিকেটর (🔥) যুক্ত করা হয়েছে।
    - অ্যাডমিন প্যানেলে ইউজার ডিটেইলস পেজে স্ট্রিক সংখ্যা দেখার সুবিধা যুক্ত করা হয়েছে।

### টেস্টিং
- কুইজ সাবমিট করার পর স্ট্রিক ডাটাবেসে আপডেট হচ্ছে কিনা এবং রিফ্রেশ করলে পুনরায় বাড়ছে না তা নিশ্চিত করা হয়েছে।
- প্রগ্রেস পেজে অর্জিত ব্যাজগুলো কালারফুল এবং বাকিগুলো গ্রে-স্কেল দেখাচ্ছে কিনা verify করা হয়েছে।

---

## 🏆 Phase 13 — Test Coverage Expansion

**Status:** ✅ Completed
**ক্যাটাগরি:** Quality Assurance / Testing
**সম্পন্ন হওয়ার তারিখ:** ২০ এপ্রিল ২০২৬

### কী করা হয়েছে
- **Unit Testing:**
    - `StreakTest.php`: ইউজার স্ট্রিক ইনক্রিমেন্ট, রিসেট এবং একই দিন একাধিকবার আপডেট না হওয়ার লজিক যাচাই করা হয়েছে।
- **Feature Testing:**
    - `RbacTest.php`: রোল এবং পারমিশন অনুযায়ী অ্যাডমিন এরিয়াতে এক্সেস কন্ট্রোল (Student vs Admin vs Super Admin) চেক করা হয়েছে।
    - `LiveExamTest.php`: লাইভ এক্সাম ইনডেক্স এক্সেস, একটিভ এক্সামে জয়েন এবং শেষ হয়ে যাওয়া এক্সামে বাধা দেওয়ার লজিক যাচাই করা হয়েছে।
    - `RegistrationTest` & `AuthenticationTest`: রিডাইরেকশন লজিক এবং রোল অ্যাসাইনমেন্ট ফিক্স করা হয়েছে।
- **Base Infrastructure:**
    - `TestCase.php`-এ `setupPermissions()` হেল্পার যুক্ত করা হয়েছে যাতে টেস্টগুলো সহজে সিমুলেটেড ডেটাবেসে রোল/পারমিশন সেটআপ করতে পারে।
    - `LiveExamFactory` তৈরি এবং `LiveExam` মডেলে `HasFactory` ট্রেইট যুক্ত করা হয়েছে।

### ফলাফল
- প্রোজেক্টের ৪ঠা এপ্রিলের পর থেকে সকল নতুন লজিক এবং আগের ক্রিটিক্যাল পাথগুলো এখন অটোমেটেড টেস্টের আওতায়।
- মোট ৪৫টি টেস্ট এবং ১০৮টি অ্যাসারশন সফলভাবে পাস হয়েছে।

---

## 🔭 Phase 14 — Laravel Telescope (Dev Observability)

**Status:** ✅ Completed
**ক্যাটাগরি:** Developer Experience
**সম্পন্ন হওয়ার তারিখ:** ২০ এপ্রিল ২০২৬

### কী করা হয়েছে
- **Installation:** `laravel/telescope` (require-dev) প্যাকেজ ইনস্টল করা হয়েছে এবং প্রয়োজনীয় assets পাবলিশ করা হয়েছে।
- **Security & Authorization:**
    - `TelescopeServiceProvider`-এ `gate()` আপডেট করা হয়েছে যাতে শুধুমাত্র `super-admin` অথবা যারা ড্যাশবোর্ড এক্সেস করতে পারে তারা টেলিস্কোপ দেখতে পায়।
    - `config/telescope.php`-এ `auth` এবং `admin` মিডলওয়্যার যুক্ত করা হয়েছে প্রোটেকশন বাড়ানোর জন্য।
- **Maintenance:** `bootstrap/app.php`-এ ডেইলি `telescope:prune` শিডিউল করা হয়েছে যাতে ডাটাবেসের সাইজ নিয়ন্ত্রণে থাকে।
- **Monitoring:** এখন থেকে সব ডাটাবেস কোয়েরি, রিকোয়েস্ট, এক্সেপশন এবং শেডিউলড টাস্ক টেলিস্কোপের মাধ্যমে `/telescope` পাথে মনিটর করা যাবে।

---

## ✨ Phase 15 — Code Quality & PSR-12 Final Pass

**Status:** ✅ Completed
**ক্যাটাগরি:** Code Quality
**সম্পন্ন হওয়ার তারিখ:** ২০ এপ্রিল ২০২৬

### কী করা হবে
- `php artisan pint` দিয়ে পুরো codebase final formatting pass করা হবে।
- সব controller-এ DocBlock comments আছে কিনা verify করা হবে।
- `config/quiz.php`-এ hard-coded values আছে কিনা scan করা হবে (যেমন passing percentage)।
- `routes/web.php` audit করে unused বা duplicate routes ছাঁটাই করা হবে।
- `README.md` আপডেট করা হবে — current feature list, local setup guide।
- `docs/walkthrough.md`-এ সব নতুন phase যোগ করা হবে।

### টেস্টিং
- `php artisan test` সব test pass।
- `php artisan route:list` দেখে কোনো orphan route নেই।

---

## 📌 Progress Legend

| Icon | অর্থ |
|------|------|
| ⬜ Pending | এখনো শুরু হয়নি |
| 🔄 In Progress | কাজ চলছে |
| ✅ Completed | সম্পন্ন হয়েছে |
| ⏭️ Skipped | বাদ দেওয়া হয়েছে (কারণসহ) |

---

*Plan তৈরির তারিখ: ১৯ এপ্রিল ২০২৬*
*পরবর্তী ধাপ: Payment Gateway (SSLCommerz) integration — এই upgrade plan-এর বাইরে।*
