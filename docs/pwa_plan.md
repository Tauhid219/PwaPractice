# Progressive Web App (PWA) Integration Plan

এই প্ল্যানে আমরা আমাদের Kider Laravel প্রজেক্টকে একটি Progressive Web App (PWA) তে রূপান্তরিত করবো।

---

## PWA কি?

Progressive Web App (PWA) হলো এমন একটি ওয়েব অ্যাপ্লিকেশন যা মোবাইল অ্যাপের মতো ব্যবহারকারীর ডিভাইসে **ইন্সটল** করা যায়, **অফলাইনেও** কাজ করে এবং **পুশ নোটিফিকেশন** পাঠাতে পারে। PWA তৈরির জন্য তিনটি মূল উপাদান দরকার:

1. **Web App Manifest** (`manifest.json`)
2. **Service Worker** (`sw.js`)
3. **HTTPS** (লোকালে `localhost` চলবে)

---

## Step 1: Web App Manifest তৈরি করা

**ফাইল:** `public/manifest.json`

Manifest ফাইলটি ব্রাউজারকে জানায় যে এটি একটি ইন্সটলযোগ্য অ্যাপ। এতে অ্যাপের নাম, আইকন, থিম কালার, ডিসপ্লে মোড ইত্যাদি থাকবে।

**কাজ:**
- `public/manifest.json` ফাইল তৈরি করতে হবে
- এতে নিচের তথ্যগুলো দিতে হবে:
  - `name`: "Kider - Preschool"
  - `short_name`: "Kider"
  - `start_url`: "/"
  - `display`: "standalone"
  - `background_color`, `theme_color`
  - `icons`: বিভিন্ন সাইজের আইকন (192x192, 512x512)

---

## Step 2: PWA আইকন তৈরি করা

**ফোল্ডার:** `public/icons/`

ইন্সটল হওয়ার পরে হোম স্ক্রিনে আইকন দেখানোর জন্য বিভিন্ন সাইজের আইকন লাগবে।

**কাজ:**
- `public/icons/` ফোল্ডার তৈরি করতে হবে
- নিচের সাইজগুলোর আইকন তৈরি করতে হবে:
  - `icon-72x72.png`
  - `icon-96x96.png`
  - `icon-128x128.png`
  - `icon-144x144.png`
  - `icon-152x152.png`
  - `icon-192x192.png`
  - `icon-384x384.png`
  - `icon-512x512.png`

---

## Step 3: Service Worker তৈরি করা

**ফাইল:** `public/sw.js`

Service Worker হলো ব্যাকগ্রাউন্ডে চলা একটি JavaScript ফাইল। এটি পেজ ক্যাশ করে অফলাইনে দেখানোর কাজ করে।

**কাজ:**
- `public/sw.js` ফাইল তৈরি করতে হবে
- ক্যাশ স্ট্রাটেজি ঠিক করতে হবে:
  - **Install event:** প্রয়োজনীয় CSS, JS, ইমেজ ক্যাশে রাখা (App Shell)
  - **Fetch event:** আগে ক্যাশ থেকে দেখানোর চেষ্টা করা, না পেলে নেটওয়ার্ক থেকে আনা (Cache First strategy)
  - **Activate event:** পুরনো ক্যাশ পরিষ্কার করা
- একটি অফলাইন ফলব্যাক পেজ (`public/offline.html`) তৈরি করতে হবে যাতে ইন্টারনেট না থাকলে ইউজার একটি সুন্দর মেসেজ দেখে

---

## Step 4: Master Layout এ Manifest ও Service Worker যুক্ত করা

**ফাইল:** `resources/views/frontend/layouts/master.blade.php`

**কাজ:**
- `<head>` সেকশনে manifest লিংক করতে হবে:
  ```html
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#FE5D37">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Kider">
  <link rel="apple-touch-icon" href="{{ asset('icons/icon-152x152.png') }}">
  ```
- `</body>` এর আগে Service Worker রেজিস্টার করার JavaScript যুক্ত করতে হবে:
  ```html
  <script>
      if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register('/sw.js')
              .then(reg => console.log('Service Worker registered:', reg.scope))
              .catch(err => console.log('Service Worker registration failed:', err));
      }
  </script>
  ```

---

## Step 5: Offline ফলব্যাক পেজ তৈরি করা

**ফাইল:** `public/offline.html`

যখন ইউজার অফলাইনে থাকবে এবং ক্যাশে পেজটি নেই, তখন এই পেজটি দেখাবে।

**কাজ:**
- একটি সুন্দর ডিজাইনের HTML পেজ তৈরি করতে হবে
- "You are offline" মেসেজ দেখাতে হবে
- হোমে ফিরে যাওয়ার একটি বাটন রাখতে হবে

---

## সারাংশ — কোন ফাইলে কি হবে:

| ফাইল | কাজ |
|---|---|
| `public/manifest.json` | [NEW] অ্যাপের মেটাডাটা |
| `public/sw.js` | [NEW] Service Worker (ক্যাশিং ও অফলাইন) |
| `public/offline.html` | [NEW] অফলাইন ফলব্যাক পেজ |
| `public/icons/` | [NEW] PWA আইকনগুলো |
| `master.blade.php` | [MODIFY] Manifest লিংক ও SW রেজিস্ট্রেশন যোগ |

---

## ভেরিফিকেশন (Verification)

### ব্রাউজারে টেস্ট
1. `php artisan serve` দিয়ে প্রজেক্ট রান করে ব্রাউজারে `http://localhost:8000` ওপেন করতে হবে
2. Chrome DevTools → **Application** ট্যাব ওপেন করতে হবে:
   - **Manifest** সেকশনে দেখতে হবে manifest ঠিকমত লোড হচ্ছে কিনা
   - **Service Workers** সেকশনে দেখতে হবে SW registered এবং active আছে কিনা
   - **Cache Storage** সেকশনে cached ফাইলগুলো আছে কিনা দেখতে হবে
3. Chrome এর অ্যাড্রেস বারে **ইন্সটল আইকন** আসছে কিনা দেখতে হবে
4. DevTools → Network ট্যাবে **Offline** চেক করে পেজ রিলোড করলে অফলাইন পেজ বা ক্যাশ থেকে কন্টেন্ট দেখাচ্ছে কিনা পরীক্ষা করতে হবে

### Lighthouse Audit
1. Chrome DevTools → **Lighthouse** ট্যাব ওপেন করে **PWA** ক্যাটাগরি সিলেক্ট করে অডিট চালাতে হবে
2. সব PWA চেকলিস্ট পাস করছে কিনা দেখতে হবে
