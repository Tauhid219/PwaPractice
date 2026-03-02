# 📝 Changelog — জিনিয়াস কিডস কুইজ অ্যাপ

সমস্ত উল্লেখযোগ্য পরিবর্তন এই ফাইলে নথিভুক্ত করা হবে।

---

## [Unreleased]

### ২৮ ফেব্রুয়ারি ২০২৬

#### 📋 Planning
- প্রজেক্ট রোডম্যাপ তৈরি (`docs/quiz-app-roadmap.md`)
- Walkthrough ডকুমেন্ট তৈরি (`docs/walkthrough.md`)
- এই Changelog ফাইল তৈরি (`docs/changelog.md`)
- প্রশ্নের ফরম্যাট নির্ধারণ: প্রশ্ন + এক শব্দ/বাক্যে উত্তর
- ডেটাবেস স্কিমা ডিজাইন: `categories`, `chapters`, `questions`
- ৬-ধাপের incremental development plan ফাইনাল

---

#### 💾 Database & Backend (Phase 1)
- `Category`, `Chapter`, এবং `Question` Model ও Migration তৈরি
- ডাটাবেস টেবিলগুলোর মধ্যে সম্পর্ক স্থাপন (Category -> Chapters -> Questions)
- `CategorySeeder` দিয়ে ৫টি মূল বিষয় ও ৭৫টি ডামি প্রশ্ন ডাটাবেসে সেভ করা হয়েছে

---

#### 🖥️ Frontend UI (Phase 2)
- হোমপেজ আপডেট করে ক্যাটাগরি গ্রিড যুক্ত করা হয়েছে
- ডাইনামিক রাউটিং: `/category/{slug}` এবং `/chapter/{slug}` যোগ করা হয়েছে
- `chapters.blade.php` ভিউ তৈরি করে অধ্যায়ের তালিকা দেখানো হয়েছে 
- `questions.blade.php` তৈরি করে प्रश्न-উত্তর Accordion স্টাইলে দেখানো হয়েছে
- `FrontendController` এ প্রয়োজনীয় ডাটা কুয়েরি লজিক যোগ করা হয়েছে

---

#### 📱 Mobile/PWA Features (Phase 3)
- `manifest.json` ফাইলটিতে "জিনিয়াস কিডস" ব্র্যান্ডিং যোগ করা হয়েছে
- `offline.html` ফাইলটি বাংলায় অনুবাদ ও রিডিজাইন করা হয়েছে
- `sw.js` এ ক্যাশিং স্ট্র্যাটেজি আপডেট (Network-first for HTML, Cache-first for assets) করা হয়েছে যাতে কুইজ অ্যাপ অফলাইনেও কাজ করে

#### 🐛 Bugfixes (Phase 3.1)
- cPanel সার্ভার (Production environment) এ `fakerphp/faker` প্যাকেজটি না থাকায় `php artisan migrate:fresh --seed` কমান্ডে এরর আসছিলো। তাই সব Factory ফাইল থেকে Faker এর নির্ভরশীলতা বাদ দিয়ে কোর PHP-এর `Str::random()` এবং `rand()` ফাংশন ব্যবহার করে ডামি ডেটা জেনারেট করা হয়েছে।
- মোবাইল বা ব্রাউজার থেকে PWA ইন্সটলেশন প্রম্পট স্বয়ংক্রিয়ভাবে না আসায় 헤ডার (Navbar) এ একটি "অ্যাপ ইনস্টল করুন" বাটন যুক্ত করা হয়েছে যা `beforeinstallprompt` ইভেন্ট ফায়ার হলে দৃশ্যমান হবে। 
- অফলাইনে থাকার সময় ক্যাশ মেমোরি থেকে ডাইনামিক রাউট (Route params) সঠিকভাবে লোড না হওয়ার (Query StringMismatch) সমস্যার সমাধান করতে `sw.js` ফাইলে `ignoreSearch: true` অপশন যোগ করা হয়েছে এবং Cache ভার্সন আপডেট করা হয়েছে।

#### 📱 Mobile/PWA Features (Phase 3.2: Full Offline Sync)
- API endpoint `GET /offline-urls` যোগ করা হয়েছে, যা ডাটাবেসের সমস্ত ক্যাটাগরি ও অধ্যায়ের লিংক একসাথে রিটার্ন করে।
- `sw.js` আপডেট করা হয়েছে যাতে অ্যাপ ইন্সটল হওয়ার সময় ব্যাকগ্রাউন্ডে সমস্ত ডাটাবেস কন্টেন্ট ক্যাশ করে নেয়। এতে অনলাইনে ভিজিট না করলেও অফলাইনে যেকোনো ডেটা দেখা যাবে।
- ইউজার ইন্টারফেসে (Frontend) ডাউনলোডিং প্রোগ্রেস বার (Toast) এবং সাকসেস মেসেজ যোগ করা হয়েছে, যাতে বোঝা যায় অফলাইন ডেটা লোড হয়ে গেছে কিনা।

#### 🐛 Bugfixes (Phase 3.3)
- Route name পরিবর্তনের (`frontend.chapters` এবং `frontend.questions`) কারণে Blade File-এ যে 500 Server Error আসছিলো, তা ফিক্স করা হয়েছে (Route name আপডেট করে `category.chapters` এবং `chapter.questions` করা হয়েছে)।

#### 🐛 Bugfixes (Phase 3.4)
- Route Update করার সময় হোমপেজের Category এবং Chapter লিস্টের HTML স্ট্রাকচার (CSS classes) ভেঙে গিয়েছিলো, যা আগের অরিজিনাল ডিজাইনে রিস্টোর করা হয়েছে।

#### 🐛 Bugfixes (Phase 3.5: Optimization)
- Service Worker প্রতিবার পেজ রিফ্রেশে লোড হওয়া (cache-busting query param `?v=`) বন্ধ করা হয়েছে, ফলে অফলাইন সিঙ্ক এখন প্রতি পেজ ভিউতে হবে না।
- অফলাইন ডাটাবেস ডাউনলোডের লজিক পরিবর্তন করে ম্যানুয়াল করা হয়েছে: এখন সাইট ব্রাউজ করার সময় ডাটা অপচয় হবে না, শুধু **"অ্যাপ ইনস্টল করুন"** বাটনে ক্লিক করলে তবেই পুরো অফলাইন কন্টেন্ট একসাথে ডাউনলোড হবে।
- ব্যাকগ্রাউন্ড 다운로드 এর সময় ইউজার যাতে অন্য পেজে চলে না যান, সেজন্য একটি ফুল-স্ক্রিন ব্লক করা প্রোগ্রেস ওভারলে (Spinner & Overlay) যোগ করা হয়েছে। ডাউনলোড ১০০% হলে সাকসেস মেসেজ আসবে।
- অধ্যায় লিস্টের ডিজাইন থেকে বই আইকনের নিচে থাকা ডাবল লেখা (অধ্যায় X) সরিয়ে ফেলা হয়েছে।

#### 🛠️ Refactoring (Phase 3.6: Best Practices)
- Laravel-এর PSR-12 কোডিং স্ট্যান্ডার্ড মেনে `FrontendController`, `CategorySeeder`, `ChapterFactory` এবং `QuestionFactory` ফাইলে ইনলাইন মডেল কল (`\App\Models\Category`) বাদ দিয়ে ফাইলের শুরুতে `use` স্টেটমেন্ট ডিক্লেয়ার করা হয়েছে।
- `FrontendController` এর মেথডগুলোর নাম ভুলবশত পরিবর্তন হয়ে যাওয়ায় যে রাউটিং এরর হয়েছিলো, তা ফিক্স করা হয়েছে।

---

<!-- পরবর্তী এন্ট্রি এখানে যোগ হবে -->
