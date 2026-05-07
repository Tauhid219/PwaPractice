# PWA Verification Checklist

এই checklist Phase 5 verification-এর জন্য।  
লক্ষ্য হলো implementation complete হওয়ার পরে local, mobile device, এবং cPanel deploy-এর পরে একইভাবে test করা।

## Status Legend

- `[ ]` শুরু হয়নি
- `[-]` চলমান
- `[x]` সম্পন্ন
- `[!]` সমস্যা / blocker

---

## 1. Local Sanity Checks

- `[x]` `public/manifest.json` valid JSON
- `[x]` manifest values present:
  - `start_url=/?source=pwa`
  - `display=standalone`
  - `lang=bn`
- `[x]` icon files dimension check:
  - `icon-180x180.png`
  - `icon-192x192.png`
  - `icon-512x512.png`
  - `favicon.png`
- `[x]` offline route exists: `/offline`
- `[x]` auth-gated routes confirmed:
  - `/profile/progress`
  - `/live-exams`

## 2. Local Laravel Maintenance Check

- `[!]` `php artisan optimize:clear` full success হয়নি

### Blocker Note

লোকাল environment-এ cache clear চলাকালে MySQL connection fail করেছে:

- host: `127.0.0.1`
- port: `3306`
- database: `pwapractice`

এটা code regression না; local DB unavailable থাকায় command পুরোপুরি শেষ হয়নি।

---

## 3. Android Chrome Test Checklist

### Fresh State Preparation

- `[ ]` installed PWA uninstall করুন
- `[ ]` Chrome site data clear করুন
- `[ ]` `https://your-domain` fresh open করুন
- `[ ]` প্রথম load-এর পরে tab close করে আবার open করুন

### Installability Check

- `[ ]` top menu install button দেখা যাচ্ছে কি না
- `[ ]` button click-এ native install prompt আসে কি না
- `[ ]` prompt accept করলে overlay + install complete flow ঠিক আছে কি না
- `[ ]` installed app home screen icon থেকে standalone mode-এ খুলছে কি না
- `[ ]` app installed হলে top install button hide হচ্ছে কি না

### Manifest / Launch Check

- `[ ]` app icon crisp দেখাচ্ছে কি না
- `[ ]` splash/launch state acceptable কি না
- `[ ]` address bar ছাড়া standalone launch হচ্ছে কি না
- `[ ]` app name সঠিক দেখাচ্ছে কি না

### Offline Check

- `[ ]` internet বন্ধ করে একটি নতুন page navigation try করুন
- `[ ]` dedicated offline page খুলছে কি না
- `[ ]` offline page Bangla copy readable কি না
- `[ ]` `আবার চেষ্টা করুন` button কাজ করছে কি না
- `[ ]` internet ফিরে এলে page auto-refresh হচ্ছে কি না

---

## 4. iPhone Safari Checklist

- `[ ]` Safari-তে site open করুন
- `[ ]` install button/manual instruction path expected কি না
- `[ ]` `Add to Home Screen` flow clear কি না
- `[ ]` home screen icon acceptable কি না
- `[ ]` app standalone mode-এ launch হচ্ছে কি না
- `[ ]` offline গেলে dedicated offline page আসে কি না

---

## 5. Desktop Browser Checklist

- `[ ]` Chrome DevTools > Application > Manifest loads correctly
- `[ ]` Service worker registered and active
- `[ ]` Cache Storage-এ expected cache entries only
- `[ ]` no risky dynamic/auth content cached
- `[ ]` offline navigation goes to `/offline`

---

## 6. cPanel Deploy Checklist

- `[ ]` local changes commit করুন
- `[ ]` `git push origin main`
- `[ ]` cPanel terminal:
  1. `cd ~/pwapractice`
  2. `git pull origin main`
  3. `php artisan optimize:clear`
- `[ ]` live site hard refresh
- `[ ]` mobile device-এ reinstall test

### cPanel Note

Subdomain live root symlink হয়ে `~/pwapractice/public` serve করছে।  
তাই `public/*` changes-এর জন্য আলাদা file copy step expected না।

---

## 7. Final Acceptance Criteria

- `[ ]` Android Chrome-এ proper install path reliable
- `[ ]` installed app standalone mode-এ launch হয়
- `[ ]` offline গেলে app content না দেখিয়ে dedicated fallback page দেখায়
- `[ ]` fallback copy Bangla-first এবং পরিষ্কার
- `[ ]` manifest/icon/meta set coherent
- `[ ]` deploy flow repeatable

---

## Verification Notes

- এই checklist implementation-এর পরে multiple rounds-এ reuse করা যাবে
- Phase 6 শুরু করার আগে ideally Android Chrome + live domain check complete হওয়া ভালো
