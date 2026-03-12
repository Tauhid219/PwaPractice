# 🚀 Optimization and Scaling Implementation Plan

এই প্ল্যানটি ৯৮০+ প্রশ্ন এবং উচ্চ ইউজার প্রেশার হ্যান্ডেল করার জন্য প্রয়োজনীয় টেকনিক্যাল পরিবর্তনগুলো নিশ্চিত করবে।

## ১. ডেটাবেস অপ্টিমাইজেশন (Database Indexing)
সার্চিং এবং কুইজ লোডিং দ্রুত করার জন্য ইনডেক্সিং যোগ করা হবে।

- [ ] **Migration:** `add_indexes_to_optimization_tables` মাইগ্রেশন তৈরি করা।
    - `questions` টেবিলে `category_id` এবং `level_id` এ ইনডেক্স।
    - `user_progress` টেবিলে `user_id`, `category_id`, এবং `level_id` এ কম্পোজিট ইনডেক্স।
    - `quiz_attempts` টেবিলে `user_id` এবং `level_id` এ ইনডেক্স।

## ২. অ্যাপ্লিকেশন ক্যাশিং (Caching)
বারবার ডাটাবেস কোয়েরি কমানোর জন্য লারাভেল ক্যাশ ব্যবহার করা হবে।

- [ ] **Frontend Controller Updates:**
    - `CategoryController`: ক্যাটাগরি লিস্ট এবং ডিটেইলস ক্যাশ করা।
    - `QuizController`: প্রশ্নের তালিকা ক্যাশ থেকে লোড করা।
- [ ] **Cache Clearing Logic:** এডমিন প্যানেল থেকে প্রশ্ন বা ক্যাটাগরি আপডেট করলে যেন ক্যাশ ক্লিয়ার হয় তা নিশ্চিত করা (`Cache::forget`)।

## ৩. PWA অপ্টিমাইজেশন (Service Worker)
অফলাইন এক্সপেরিয়েন্স এবং পারফরম্যান্স বাড়ানোর জন্য `sw.js` আপডেট করা।

- [ ] **sw.js Update:**
    - API রেসপন্সগুলোর জন্য "Stale-While-Revalidate" স্ট্র্যাটেজি ইমপ্লিমেন্ট করা।
    - গুরুত্বপূর্ণ ডেটা (Categories/Questions) অফলাইনেও যেন আংশিক দেখা যায় তার ব্যবস্থা করা।

## ৪. ব্যাকগ্রাউন্ড প্রসেসিং (Queues for Live Exam)
লাইভ এক্সাম চলাকালীন সার্ভার লোড কমানো।

- [ ] **Queue Setup:**
    - `.env` ফাইলে `QUEUE_CONNECTION=database` ব্যবহার করা (যেহেতু `jobs` টেবিলটি অলরেডি আছে)।
    - লাইভ এক্সাম স্কোর সাবমিশন প্রসেসকে জব (`Job`) হিসেবে হ্যান্ডেল করা।

---

## ভেরিফিকেশন প্ল্যান
- [ ] `php artisan migrate` রান করে ইনডেক্স চেক করা।
- [ ] `Laravel Telescope` বা `Query Log` দিয়ে চেক করা ক্যাশ কাজ করছে কি না।
- [ ] ব্রাউজার কনসোল এবং নেটওয়ার্ক ট্যাব থেকে Service Worker ক্যাশিং ভেরিফাই করা।
