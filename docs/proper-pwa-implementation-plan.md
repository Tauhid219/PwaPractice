# Proper PWA Implementation Plan

এই প্ল্যানের লক্ষ্য হলো `PwaPractice`-কে একটি আরও reliable, predictable, এবং polished PWA হিসেবে harden করা।  
আমরা phase by phase এগোবো। প্রতিটি phase শেষ হওয়ার পর আপনার confirmation ছাড়া পরের phase শুরু হবে না।

## Status Legend

- `[ ]` শুরু হয়নি
- `[-]` চলমান
- `[x]` সম্পন্ন
- `[!]` blocker / decision needed

## Overall Goal

- install experience browser reality-এর সাথে aligned করা
- service worker cache behavior safe করা
- manifest, icon, shortcut, launch behavior polish করা
- app-টিকে fully online-only রাখা
- user offline হলে একটি সুন্দর dedicated fallback page দেখানো
- offline fallback deterministic করা
- real-device verification checklist establish করা

## Phase Gate Rule

একটি phase complete না হওয়া পর্যন্ত পরের phase শুরু করা হবে না।  
প্রতিটি phase শেষ হলে এই ফাইলেই marker update করা হবে।

---

## Phase 0: Baseline Audit And Freeze
**Status:** `[x]`

### Goal
বর্তমান PWA behavior exactly document করা, যাতে change-এর পরে regression বোঝা যায়।

### Tasks
- `[x]` current `manifest.json`, `sw.js`, install UI, offline route, meta tags inventory করা
- `[x]` Android Chrome install behavior note করা
- `[x]` iOS Safari fallback expectation note করা
- `[x]` current known issues list freeze করা
- `[x]` cPanel deploy note add করা, কারণ `public` symlinked live root হিসেবে কাজ করছে

### Deliverables
- current behavior summary
- known issue checklist
- baseline assumptions

### Exit Criteria
- PWA surface area fully listed
- কোন files/routes touched হবে তা clear

### Baseline Summary

- `public/manifest.json` এ `id`, `start_url`, `scope`, `display: "standalone"`, `display_override`, icons, shortcuts, screenshots আছে
- `public/sw.js` এ current cache version `genius-kids-pwa-v12`
- service worker static asset precache + navigation fallback + broader GET caching use করছে
- frontend master layout-এ manifest link, theme color, mobile web app meta, apple meta, service worker registration, install UI, appinstalled handling, offline banner আছে
- `/offline` route public
- manifest shortcuts currently auth-gated routes target করছে

### Frozen Current Behavior

#### Install Behavior
- Android Chrome-এ top menu install button দেখা যেতে পারে even when `beforeinstallprompt` not yet available
- `beforeinstallprompt` available হলে custom overlay/progress-এর পর native prompt path use হয়
- prompt unavailable হলে Android-এ overlay দেখিয়ে user-কে browser menu install path-এর দিকে guide করা হয়
- iOS Safari-তে manual `Add to Home Screen` fallback expectation

#### Offline Behavior
- app policy এখন intended online-only
- navigation request offline হলে `/offline` fallback page serve হওয়ার চেষ্টা হয়
- static assets cache-first
- non-static GET responses broader stale-while-revalidate path-এ যাচ্ছে

### Frozen Known Issues

- install UX পুরোপুরি browser-native install availability-এর সাথে aligned না
- service worker cache scope future authenticated/dynamic data-এর জন্য একটু বেশি broad
- manifest shortcuts auth-required route-এ যাচ্ছে
- iOS icon/meta coverage minimal
- encoding/browser-render verification এখনও live-devtools level-এ করা হয়নি

### Files In Scope For Upcoming Phases

- `public/manifest.json`
- `public/sw.js`
- `resources/views/frontend/layouts/master.blade.php`
- `resources/views/offline.blade.php`
- `routes/web.php`

### Deploy Note

- cPanel setup-এ live subdomain `lab.rezatauhid.top` symlink হয়ে `~/pwapractice/public` serve করছে
- তাই `public/*` changes deploy করতে আলাদা copy step লাগার কথা না
- expected deploy flow:
  1. `git push origin main`
  2. server-এ `cd ~/pwapractice`
  3. `git pull origin main`
  4. `php artisan optimize:clear`

---

## Phase 1: Service Worker Hardening
**Status:** `[x]`

### Goal
`public/sw.js`-কে safer এবং more predictable করা, especially cache scope ও response handling-এর দিক থেকে।

### Tasks
- `[x]` current cache strategy classify করা: shell assets, navigations, fallback, dynamic GET
- `[x]` authenticated/personalized responses accidental cache risk remove করা
- `[x]` cache whitelist explicit করা
- `[x]` opaque / failed / non-200 response caching rules tighten করা
- `[x]` offline fallback path deterministic রাখা
- `[x]` cache versioning convention document করা

### Files
- `public/sw.js`
- `resources/views/offline.blade.php`

### Deliverables
- hardened service worker
- explicit caching rules
- updated cache version

### Exit Criteria
- service worker only intended assets/resources cache করবে
- offline fallback predictable হবে
- risky dynamic caching remove বা restrict হবে

### Phase 1 Summary

- `public/sw.js` cache version `genius-kids-pwa-v13` করা হয়েছে
- broad stale-while-revalidate dynamic GET caching remove করা হয়েছে
- service worker এখন only:
  - explicit precached URLs
  - same-origin static asset requests
  cache করবে
- cross-origin request cache করা হবে না
- dynamic app/document/API-like GET request service worker আর cache করবে না
- navigation request online-first থাকবে, failure হলে dedicated `/offline` fallback দেবে
- cache write only successful same-origin basic response-এর জন্য allow করা হয়েছে

### Notes

- এই phase-এ `resources/views/offline.blade.php` change করা হয়নি; fallback policy stable রাখা হয়েছে
- next phase-এ install flow cleanup করার সময় service worker behavior আবার manually verify করতে হবে

---

## Phase 2: Install Flow Cleanup
**Status:** `[x]`

### Goal
Install UX-কে সুন্দর কিন্তু honest করা, যাতে browser-supported install আর manual fallback cleanly আলাদা থাকে।

### Tasks
- `[x]` `beforeinstallprompt` availability-based UI logic refine করা
- `[x]` Android Chrome install button visibility rules revisit করা
- `[x]` misleading progress flow remove বা relabel করা
- `[x]` supported install path বনাম manual fallback path আলাদা message দেওয়া
- `[x]` installed state detection refine করা
- `[x]` repeat-click / dismiss / appinstalled event handling tidy করা

### Files
- `resources/views/frontend/layouts/master.blade.php`
- related frontend partials if needed

### Deliverables
- cleaner install trigger behavior
- improved install state UX
- reduced user confusion

### Exit Criteria
- install button behavior consistent হবে
- unsupported native prompt path-এ misleading success feel থাকবে না
- app installed হলে button properly hide হবে

### Phase 2 Summary

- install button visibility tighten করা হয়েছে
- Android Chrome-এ button এখন native `beforeinstallprompt` পাওয়া গেলে visible হবে
- iOS Safari-তে manual add-to-home-screen fallback button visible থাকতে পারবে
- progress overlay এখন only real native install prompt path-এ use হবে
- Android fallback path-এ আর fake install progress/success feel দেখানো হবে না
- dismiss/appinstalled state-এ button visibility আরও consistent হয়েছে

### Notes

- এই phase-এ top menu install entry retained আছে
- next phase-এ manifest/icon/launch polish করার পরে real-device behavior আবার verify করা উচিত

---

## Phase 3: Manifest And Launch Polish
**Status:** `[x]`

### Goal
Manifest-কে production-ready করা, launch feel improve করা, এবং shortcut behavior polish করা।

### Tasks
- `[x]` manifest identity fields verify/update করা (`id`, `name`, `short_name`, `start_url`, `scope`)
- `[x]` icon set review করা
- `[x]` iOS-friendly icon coverage improve করা
- `[x]` auth-gated shortcuts keep/remove/replace decide করা
- `[x]` screenshot references verify করা
- `[x]` theme/background/orientation fields cross-check করা
- `[x]` native-like launch behavior verify করার জন্য required meta tags align করা

### Files
- `public/manifest.json`
- `resources/views/frontend/layouts/master.blade.php`
- `public/icons/*`

### Deliverables
- cleaned manifest
- improved icon/meta coverage
- safer shortcuts

### Exit Criteria
- manifest fields coherent হবে
- shortcut behavior intentional হবে
- installed app launch feel improved হবে

### Phase 3 Summary

- sub-agent audit ব্যবহার করে manifest/launch gaps cross-check করা হয়েছে
- `public/manifest.json`-এ `lang`, `dir`, `prefer_related_applications` যোগ করা হয়েছে
- auth-gated shortcuts remove করা হয়েছে
- misleading screenshot entries remove করা হয়েছে, কারণ current assets real app screenshots ছিল না
- icon purpose `any` করা হয়েছে, যাতে unverified maskable claim avoid করা যায়
- broken/mismatched PNG icon files regenerate করা হয়েছে true `192x192`, `512x512`, এবং iOS-friendly `180x180` size-এ
- `favicon.png` coherent icon family-এর অংশ হিসেবে regenerate করা হয়েছে
- `resources/views/frontend/layouts/master.blade.php`-এ document language `bn` করা হয়েছে
- iOS touch icon `180x180` করা হয়েছে
- launch/meta polish-এর জন্য description এবং `format-detection` meta update করা হয়েছে

### Notes

- proper real UI screenshots future iteration-এ add করা উচিত
- dedicated maskable-safe icon art future branding pass-এ add করা ভালো হবে
- iOS startup image assets এই phase-এ add করা হয়নি

---

## Phase 4: Offline Experience Policy
**Status:** `[x]`

### Goal
এই app পুরোপুরি online-only থাকবে। user offline হলে app content serve করার চেষ্টা না করে একটি সুন্দর, পরিষ্কার, dedicated offline message page দেখানো হবে।

### Tasks
- `[x]` offline policy explicitly freeze করা: no offline app usage, fallback page only
- `[x]` `/offline` page messaging review করা
- `[x]` retry/home/navigation actions verify করা
- `[x]` offline banner behavior review করা
- `[x]` Bangla copy consistency check করা
- `[x]` encoding safety verify করা

### Files
- `resources/views/offline.blade.php`
- `resources/views/frontend/layouts/master.blade.php`
- `public/sw.js`
- `routes/web.php`

### Deliverables
- explicit online-only policy
- polished offline fallback UX
- clearer user guidance

### Exit Criteria
- offline state confusing হবে না
- offline অবস্থায় app cached content দেখাবে না; fallback page-এই যাবে
- fallback UI consistent হবে
- Bangla messaging readable থাকবে

### Phase 4 Summary

- online-only policy এখন offline UI copy-তে explicit করা হয়েছে
- `resources/views/offline.blade.php` নতুন করে polish করা হয়েছে
- offline page এখন clearly বলে যে app content online connection ছাড়া available না
- retry + home actions retained আছে
- connection ফিরে এলে offline page auto refresh করবে
- `resources/views/frontend/layouts/master.blade.php` offline banner text policy-এর সাথে align করা হয়েছে
- `public/sw.js` fallback response Bangla copy-তে align করা হয়েছে
- new iOS touch icon asset service worker precache list-এ যোগ করা হয়েছে

### Notes

- এই phase code-level complete
- live browser/device verification এখনও Phase 5-এ formal checklist অনুযায়ী করা হবে

---

## Phase 5: Verification And Device Testing
**Status:** `[x]`

### Goal
Desktop audit নয়, real installability এবং live behavior যাচাই করা।

### Tasks
- `[x]` local verification checklist তৈরি করা
- `[x]` Android Chrome test checklist তৈরি করা
- `[x]` iOS Safari manual-add checklist তৈরি করা
- `[x]` cache reset / uninstall / reinstall test flow document করা
- `[x]` cPanel deploy-after-change checklist document করা
- `[x]` final regression checklist তৈরি করা

### Deliverables
- reusable QA checklist
- deploy checklist
- acceptance criteria

### Exit Criteria
- আমরা জানবো কোন test pass হলে app-টাকে “proper enough” বলা যাবে
- future PWA changes verify করা সহজ হবে

### Phase 5 Summary

- reusable verification document তৈরি করা হয়েছে: `docs/pwa-verification-checklist.md`
- local sanity check-এ manifest parse successful
- icon dimensions locally verify করা হয়েছে
- `/offline` route present verify করা হয়েছে
- auth-gated routes verify করা হয়েছে, যাতে shortcut safety decision validated থাকে
- Android Chrome, iPhone Safari, desktop DevTools, এবং cPanel deploy-এর জন্য separate checklist যোগ করা হয়েছে

### Verification Notes

- local `php artisan optimize:clear` full success হয়নি, কারণ MySQL connection unavailable ছিল
- এই issue PWA code regression না; local environment dependency issue
- formal real-device and live-domain verification এখনও checklist-driven manual execution হিসেবে pending থাকবে

---

## Phase 6: Optional Advanced Enhancements
**Status:** `[x]`

### Goal
Base PWA polish complete হওয়ার পরে optional enhancements evaluate করা।

### Tasks
- `[x]` Web Push viability evaluate করা
- `[x]` splash screen / launch asset polish evaluate করা
- `[x]` app analytics for install funnel evaluate করা
- `[x]` richer offline read-only mode worth it কি না decide করা
- `[x]` TWA / Flutter / native wrapper future path note করা

### Deliverables
- optional roadmap
- not-now vs later decision list

### Exit Criteria
- core PWA stable হওয়ার পরে future direction documented থাকবে

### Phase 6 Summary

- optional enhancement decisions documented in `docs/pwa-advanced-enhancements-roadmap.md`
- install funnel analytics next-best optional implementation হিসেবে identified করা হয়েছে
- launch asset polish useful কিন্তু lower priority হিসেবে noted হয়েছে
- web push technically possible হলেও current product stage-এ deferred রাখা হয়েছে
- richer offline read-only mode current online-only policy-এর বিরুদ্ধে যাওয়ায় not-now list-এ রাখা হয়েছে
- TWA / Flutter / native wrapper future distribution decision হিসেবে documented হয়েছে, immediate implementation target হিসেবে নয়

### Decision Notes

- this phase was completed as a roadmap and evaluation phase, not as a feature-implementation phase
- the most practical next optional build item is install funnel analytics
- no cache policy or offline behavior was widened in this phase

---

## Suggested Execution Order

1. Phase 0
2. Phase 1
3. Phase 2
4. Phase 3
5. Phase 4
6. Phase 5
7. Phase 6

---

## Working Notes

- এই প্ল্যান execution document হিসেবে ব্যবহার হবে।
- প্রতিটি phase complete হলে `Status` marker update করা হবে।
- phase-এর ভেতরের checklist item-গুলোও update করা হবে।
- কোন phase-এ design decision লাগলে `Status: [!]` করা হবে।

---

## Current Phase

**Active Phase:** `Phase 6: Optional Advanced Enhancements`  
**Next Action:** optional roadmap complete; next implementation candidate is install funnel analytics.
