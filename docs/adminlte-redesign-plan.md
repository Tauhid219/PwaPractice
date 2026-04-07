# 🏗️ AdminLTE-3.1.0 Admin Panel Redesign Plan

এই পরিকল্পনা অনুসারে আমাদের পকেট কুইজ অ্যাপের (PwaPractice) অ্যাডমিন প্যানেলটি বর্তমান Tailwind CSS লেআউট থেকে জনপ্রিয় **AdminLTE 3.1.0** বুটস্ট্র্যাপ টেমপ্লেটে স্থানান্তরিত করা হবে।

## প্রজেক্ট তথ্য
- **মূল প্রজেক্ট:** `C:\xampp\htdocs\Laravel-Practice\PwaPractice`
- **টেমপ্লেট সোর্স:** `C:\xampp\htdocs\Templates\AdminLTE-3.1.0`
- **টারগেট লেআউট:** Bootstrap 4 ভিত্তিক AdminLTE 3.1.0

---

## 🛠️ Phases & Workload

### Phase 1: Asset Management & Master Layout Setup [✅ Completed]
> **লক্ষ্য:** AdminLTE-এর সব প্রয়োজনীয় ফাইল প্রজেক্টে যুক্ত করা এবং একটি বেস লেআউট তৈরি করা।

- [x] **Asset Migration:** `dist` এবং `plugins` ফোল্ডারগুলো `public/vendor/adminlte` ডিরেক্টরিতে কপি করা।
- [x] **Master Layout Creation:** `resources/views/layouts/admin.blade.php` নামে একটি নতুন ব্লেড ফাইল তৈরি করা (`starter.html`-কে ভিত্তি করে)।
- [x] **Asset Linking:** সিএসএস এবং জেএস ফাইলগুলোকে `asset()` হেল্পার দিয়ে লিঙ্ক করা।
- [x] **Slot/Yield Setup:** কন্টেন্ট রেন্ডার করার জন্য `@yield('content')` বা `$slot` সেটআপ করা।

---

### Phase 2: Layout Componentization [✅ Completed]
> **লক্ষ্য:** লেআউটকে ছোট ছোট অংশে ভাগ করা যাতে কোড ক্লিন থাকে।

- [x] **Navbar:** `resources/views/layouts/admin/navbar.blade.php` তৈরি করা।
- [x] **Sidebar:** `resources/views/layouts/admin/sidebar.blade.php` তৈরি করা।
- [x] **Footer:** `resources/views/layouts/admin/footer.blade.php` তৈরি করা।
- [x] **Dynamic Navigation:** সাইডবারে আমাদের ক্যাটাগরি, প্রশ্ন এবং ইউজার ম্যানেজমেন্টের লিংকগুলো ডাইনামিক করা।
- [x] **Auth Info:** সাইডবার বা নেভবারে লগইন করা অ্যাডমিনের নাম ও ছবি দেখানো।

---

### Phase 3: Core View Redesign [✅ Completed]
> **লক্ষ্য:** বর্তমান ব্লেড ফাইলগুলোকে AdminLTE-এর স্টাইলে রূপান্তর করা।

- [x] **Auth Views (Breeze):** লগইন, রেজিস্ট্রেশন এবং পাসওয়ার্ড রিসেট পেজগুলোকে AdminLTE-এর Auth ডিজাইনে রূপান্তর করা (Laravel Breeze ব্যাকএন্ড হিসেবে থাকবে)।
- [x] **Dashboard:** `admin/dashboard.blade.php` ফাইলে AdminLTE-এর "Small Box" বা "Info Box" ব্যবহার করে স্ট্যাটাস দেখানো।
- [x] **Category Management:** ক্যাটাগরি লিস্ট এবং ক্রিয়েট/এডিট ফর্মগুলো AdminLTE Cards এবং Tables-এ সাজানো।
- [x] **Question Management:** প্রশ্নের বিশাল ডেটাসেট দেখানোর জন্য AdminLTE এর টেবিল ডিজাইন ব্যবহার করা।
- [x] **User Management:** ইউজার লিস্ট এবং রোল পরিবর্তনের UI আপডেট।
- [x] **Live Exam:** লাইভ এক্সাম ম্যানেজমেন্টের ভিউগুলো নতুন ডিজাইন অনুযায়ী ফিক্স করা।

---

### Phase 4: Integration & Optimization [✅ Completed]
> **লক্ষ্য:** সবকিছু একত্রে পরীক্ষা করা এবং ডিবাগ করা।

- [x] **Form Validation Errors:** বুটস্ট্র্যাপের এরর ক্লাসের সাথে লারাভেলের ভ্যালিডেশন এরর ইনজেক্ট করা।
- [x] **Modals & JS Components:** ইমপোর্ট মোডাল বা সাইডবার টগলের জন্য প্রয়োজনীয় জেএস ফিক্স করা।
- [x] **Final QA:** সব রাউট চেক করে দেখা যে কোনো পেইজ বা ফাংশন ভেঙে গেছে কিনা।
- [x] **Roadmap Update:** আমাদের মূল রোডম্যাপে এই পরিবর্তনের আপডেট যুক্ত করা।

---

## 🎉 Migration Successful!
অ্যাডমিন প্যানেলটি এখন সফলভাবে **AdminLTE 3.1.0** থিমে স্থানান্তরিত হয়েছে। সব ফিচার এখন একটি আধুনিক এবং রেসপন্সিভ ড্যাশবোর্ডে পাওয়া যাচ্ছে।

## 🚩 Markers Definition
- 🔴 **Pending:** কাজ শুরু হয়নি।
- 🟡 **In Progress:** বর্তমানে এই ধাপের কাজ চলছে।
- ✅ **Completed:** এই ধাপের কাজ শেষ।

> [!IMPORTANT]
> আমি Phase 1 শুরু করার জন্য আপনার অনুমতির অপেক্ষা করছি। অনুমতি দিলে আমি প্রথম ধাপের কাজ শুরু করে **Marker** টি আপডেট করে পরবর্তী ধাপে এগিয়ে যাব।
