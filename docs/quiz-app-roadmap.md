# 🧒 জিনিয়াস কিডস কুইজ গাইডবুক অ্যাপ — রোডম্যাপ

> **সর্বশেষ আপডেট:** ১২ মার্চ ২০২৬

## প্রজেক্ট তথ্য

| Item | Details |
|---|---|
| **প্রজেক্ট** | PwaPractice (Laravel PWA) |
| **উদ্দেশ্য** | জিনিয়াস কিডস কুইজ প্রতিযোগিতার গাইডবুককে অ্যাপে রূপান্তর |
| **মূল বিজনেস মডেল** | প্রিমিয়াম অ্যাপ (শিক্ষার্থীরা সামান্য পেমেন্ট করে ডাউনলোড/অ্যাক্সেস পাবে) |
| **মোট ক্যাটাগরি** | ৯টি |
| **মোট প্রশ্ন** | ~৯৮০টি |
| **লার্নিং মেথড** | লেভেল-ভিত্তিক পড়াশোনা ও কুইজ (অ্যাডাপ্টিভ লার্নিং) |
| **ডেটা সোর্স** | PDF/Doc ফাইল → Google Sheets/Excel → Laravel Import |
| **ডেপ্লয়মেন্ট** | GitHub → cPanel |

## বর্তমান অবস্থা

| Item | Status |
|---|---|
| Laravel Project | ✅ সেটআপ আছে |
| PWA (Service Worker, Manifest) | ✅ কাজ করছে, অ্যাপ ইন্সটল হয় |
| Bootstrap Template ("Kider") | ✅ ফ্রন্টএন্ড আছে |
| Database Models / Basic CRUD | ✅ প্রাথমিক কাজ শেষ |
| Admin Panel | ✅ প্রাথমিক ড্যাশবোর্ড ও রাউট আছে |
| Authentication | ✅ বেসিক লগিন/রেজিস্ট্রেশন আছে |
| Level System & Quiz Logic | ✅ কাজ করছে |
| Payment Integration | ❌ নেই |

---

## 🗺️ রোডম্যাপ — ৮ টি ধাপ

### ধাপ ১: Database + Models (Updated)
> **লক্ষ্য:** বেসিক এবং অ্যাডভান্সড ফাংশনালিটির জন্য ডেটাবেস প্রস্তুত করা

**Database Structure:**
```text
categories    → id, name, slug, icon, description, order
questions     → id, category_id, question_text, option_1, option_2, option_3, answer_text, level_id
levels        → id, name, required_score_to_unlock
user_progress → user_id, category_id/level_id, status (locked, active, completed)
quiz_attempts → user_id, level_id, score, passed
```

**কাজ:**
- [x] Migrations তৈরি (categories, questions)
- [x] Models তৈরি + relationships
- [x] লেভেল (Levels) ও ইউজার প্রগ্রেস (User Progress) এর মাইগ্রেশন ও মডেল তৈরি
- [x] Seeder — স্যাম্পল প্রশ্ন দিয়ে

---

### ধাপ ২: Simple Frontend & Admin Panel
> **লক্ষ্য:** ডেটা ফ্রন্টএন্ডে ও অ্যাডমিন প্যানেলে ম্যানেজ করা

**কাজ:**
- [x] হোম পেজ — Category কার্ড/তালিকা
- [x] Chapter listing পেজ
- [x] Admin authentication & ড্যাশবোর্ড
- [x] Category / Question CRUD
- [x] Admin to Admin রোল ম্যানেজমেন্ট (User Management)
- [x] Excel/CSV Import ফিচার (৯৮০ প্রশ্ন দ্রুত ইনপুট)

---

### ধাপ ৩: Professional UI & UX
> **লক্ষ্য:** প্রিমিয়াম ইউজার এক্সপেরিয়েন্স তৈরি

**কাজ:**
- [x] ডেস্কটপ ভিউ: বামদিকে ফিক্সড ক্যাটাগরি সাইডবার
- [x] মোবাইল ভিউ: নিচে বটম নেভিগেশন বার বা হরাইজন্টাল স্লাইডার
- [x] UI Interaction Sound (বাটনে বা অ্যাকর্ডিয়নে ক্লিক করলে 'Pop' বা 'Click' সাউন্ড)

---

### ধাপ ৪: Level-based Learning System (লগিন আবশ্যক)
> **লক্ষ্য:** ইউজারদের ধাপে ধাপে শেখানো

**কাজ:**
- [x] ইউজার রেজিস্ট্রেশন/লগিন বাধ্যতামূলক করা (পড়ার জন্য)
- [x] ক্যাটাগরির ভেতরে লেভেল সিলেকশন পেজ দেখানো (প্রথম লেভেল ফ্রি/অ্যাক্সেসযোগ্য, বাকিগুলো লক)
- [x] ফ্ল্যাশকার্ড মোড বাদ দিয়ে অ্যাকর্ডিয়ন ভিউতে লেভেলভিত্তিক প্রশ্ন দেখানো (অধ্যায় লেয়ার রিমুভড)
- [x] ফ্রন্টএন্ডে স্টুডেন্ট প্রোফাইল ড্যাশবোর্ড ও রোল-ভিত্তিক রিডাইরেক্ট (Role-based auth)
- [x] "পড়েছি" (Mark as Read) অপশন যুক্ত করা (উইথ LocalStorage state)
- [ ] পড়ার প্রগ্রেস ডাটাবেসে সিংক্রোনাইজ করা (Backend Sync)
- [x] নির্দিষ্ট লেভেলের সব প্রশ্ন পড়া হলে কুইজ বাটন আনলক হবে

---

### ধাপ ৫: Quiz Logic & Live Exam 
> **লক্ষ্য:** কুইজ পাস করে লেভেল আনলক করা এবং লাইভ এক্সাম অংশ নেওয়া (MCQ ৩-অপশন ভিত্তিক)

**কাজ:**
- [x] **লেভেল কুইজ:** পঠিত প্রশ্নগুলোর উপর কুইজ নেওয়া (৩টি অপশনসহ)
- [x] Quiz Feedback Sound (সঠিক উত্তরে 'Ting', ভুল হলে 'Buzzer')
- [x] কুইজ পাশ করলে পরবর্তী লেভেল আনলক করা (Pass/Fail মেকানিজম)
- [x] **লাইভ এক্সাম (Live Exam):** একটি নির্দিষ্ট সময়ে বা উইকেন্ডে সবার জন্য লাইভ প্রতিযোগিতা
- [x] প্রগ্রেস ট্র্যাকিং ও হিস্ট্রি সংরক্ষণ
- [ ] এডমিন প্যানেলে ইউজারভিত্তিক প্রগ্রেস মনিটরিং ড্যাশবোর্ড তৈরি করা

---

### ধাপ ৬: Scaling & Optimization (Refactoring Included)
> **লক্ষ্য:** ৯৮০+ প্রশ্ন এবং উচ্চ ইউজার প্রেশার হ্যান্ডেল করার জন্য সিস্টেম অপ্টিমাইজ করা এবং কোডবেস রিফ্যাক্টর করা

**কাজ:**
- [ ] **Architecture Refactoring:** Form Request Classes ইমপ্লিমেন্ট করা (Validation আলাদা করা)
- [ ] **Logic Centralization:** উত্তর ক্লিন বা নরমালাইজ করার লজিক এক জায়গায় নিয়ে আসা (DRY Principle)
- [ ] **Security:** কন্ট্রোলারে `$request->all()` এর বদলে `$request->validated()` ব্যবহার করা
- [ ] **Configuration:** হার্ডকোডেড ভ্যালুগুলো সরিয়ে `config/quiz.php` ফাইলে নিয়ে যাওয়া
- [ ] **PSR-12 Standard:** কোড ক্লিনআপ, Alphabetical Imports এবং প্রপার ইন্ডেন্টেশন নিশ্চিত করা
- [ ] **Database Indexing:** `questions`, `user_progress` এবং `quiz_attempts` টেবিলে ইনডেক্সিং যোগ করা
- [ ] **Caching:** গুরুত্বপূর্ণ কোয়েরিগুলো (Categories, Questions) লারাভেল ক্যাশে রাখা
- [ ] **PWA Update:** API রেসপন্স ক্যাশ করার জন্য "Stale-While-Revalidate" স্ট্র্যাটেজি যোগ করা
- [ ] **Queues:** লাইভ এক্সাম স্কোর প্রসেসিং এবং বড় এক্সেল ইমপোর্ট ব্যাকগ্রাউন্ড জবে (Jobs) নিয়ে যাওয়া

---
### ধাপ ৭: PWA Configuration & Final Deployment
> **লক্ষ্য:** অ্যাপ ডাউনলোড ও অফলাইন অ্যাক্সেস

**কাজ:**
- [x] Service Worker সেটআপ করা
- [ ] "Network Only" স্ট্র্যাটেজি থেকে সরে এসে প্রয়োজন অনুযায়ী ক্যাশ (Cache) করা
- [x] প্রফেশনাল Fallback অফলাইন পেজ তৈরি করা
- [ ] পেমেন্ট ভেরিফিকেশনের পর PWA ইন্সটল প্রম্পট (Add to Home Screen) দেখানো

---

### ধাপ ৮: Monetization & Download Setup
> **লক্ষ্য:** অ্যাপ ডাউনলোড বা ফুল অ্যাক্সেসের জন্য পেমেন্ট গেটওয়ে যুক্ত করা

**কাজ:**
- [ ] পেমেন্ট গেটওয়ে ইন্টিগ্রেশন (aamarPay / bKash / SSLCommerz / Stripe ইত্যাদি)
- [ ] সামান্য পেমেন্ট করার পর অ্যাপ ডাউনলোড বা প্রিমিয়াম/ফুল অ্যাক্সেস আনলক করার লজিক
- [ ] Free Trial (প্রথম লেভেল) vs Premium User রোল সেটআপ

---


## কাজের ক্রম

```mermaid
graph TD
    A["ধাপ ১<br>DB & Models"] --> B["ধাপ ২<br>Frontend & Admin"]
    B --> C["ধাপ ৩<br>Professional UI/UX"]
    C --> D["ধাপ ৪<br>Level-Based Learning"]
    D --> E["ধাপ ৫<br>Quiz & Live Exam"]
    E --> F["ধাপ ৬<br>Scaling & Optimization"]
    F --> G["ধাপ ৭<br>Final PWA Setup"]
    G --> H["ধাপ ৮<br>Monetization (Payment)"]
```

> [!IMPORTANT]  
> পেমেন্ট মেকানিজম এবং লেভেল-লক ফিচারের জন্য ডাটাবেস কাঠামোতে কিছু বড় পরিবর্তন আনতে হবে। ধাপ ১ এর "Levels" এবং "User Progress" মডেলটি সবার আগে তৈরি করা প্রয়োজন।
