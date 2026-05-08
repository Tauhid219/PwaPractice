# PWA Advanced Enhancements Roadmap

This document captures the evaluation outcomes for Phase 6 of the PWA hardening plan.

The app's current PWA posture is intentionally:

- installable
- online-only for real content
- conservative about caching
- low-risk for authenticated traffic

Because of that, the right Phase 6 work is not "add every PWA feature". It is deciding which advanced capabilities are worth their complexity for this Laravel quiz platform.

## Decision Summary

- `Install funnel analytics`: recommended next
- `Launch asset polish`: worthwhile later
- `Web Push`: viable, but not yet justified
- `Richer offline read-only mode`: not recommended now
- `TWA / Flutter / native wrapper`: future-path only

## 1. Web Push Viability

### Current verdict

`Later, not now`

### Why

- The repo has no web-push subscription model, VAPID key setup, notification campaign flow, or user preference center.
- The current student experience is mostly pull-based: quiz, progress, and live exam entry happen when users open the app.
- Bad push timing would be worse than no push, especially for a learning product with young users or shared devices.

### What would be needed

- subscription table for browser push endpoints
- opt-in UI with clear user value
- VAPID key management
- service worker `push` and `notificationclick` handlers
- unsubscribe / invalid subscription cleanup
- admin or scheduled campaign trigger flow
- notification categories such as:
  - live exam reminder
  - streak reminder
  - new content reminder

### Recommendation

Only start web push after the product team confirms at least one strong notification use case with timing rules and opt-out behavior.

## 2. Splash Screen / Launch Asset Polish

### Current verdict

`Yes, but low urgency`

### Why

- The app is already installable and launches correctly.
- The biggest remaining polish gaps are branding quality, not platform correctness.

### Worthwhile improvements

- add a dedicated maskable icon set
- capture real app screenshots and restore manifest screenshots intentionally
- create a more deliberate icon family for Android home screen and splash contexts
- optionally add iOS startup images only if real-device testing shows a visible brand quality issue

### Recommendation

Do this after analytics instrumentation, because launch polish improves perception but does not yet improve product insight.

## 3. Install Funnel Analytics

### Current verdict

`Recommended next implementation`

### Why

- The app already has custom install UI and install-state logic in the frontend layout.
- That makes install funnel events measurable with relatively small code changes.
- The admin dashboard already contains analytics-oriented thinking, so this fits the product direction.

### Suggested events

- `pwa_install_button_shown`
- `pwa_install_button_clicked`
- `pwa_beforeinstallprompt_available`
- `pwa_install_prompt_accepted`
- `pwa_install_prompt_dismissed`
- `pwa_appinstalled`
- `pwa_offline_fallback_viewed`

### Suggested implementation shape

- lightweight POST endpoint for event intake
- dedicated database table for install and PWA lifecycle events
- rate limiting or coarse deduping for noisy events
- simple admin summary card or report later

### Recommendation

This is the best next optional enhancement because it gives product insight without changing the online-only content model.

## 4. Richer Offline Read-Only Mode

### Current verdict

`Not recommended now`

### Why

- The current PWA policy is intentionally online-only.
- Quiz content, progress, live exams, and profile state are all better served fresh from the server.
- The project previously moved away from broad offline sync and dynamic route caching, which reduced risk and confusion.

### Risks if reintroduced

- stale quiz or exam data
- inconsistent auth behavior
- difficult cache invalidation
- user confusion when some screens work offline and others do not

### Narrow exception that could be acceptable later

If needed, a tiny offline-safe read-only surface could exist for:

- a branded offline help page
- troubleshooting tips
- install instructions

That should not be treated as an offline quiz mode.

## 5. TWA / Flutter / Native Wrapper Future Path

### Current verdict

`Document only`

### TWA

Good only if Android Play Store distribution becomes a business goal. It keeps the web app as the primary product surface.

### Flutter or native wrapper

Only worth considering if the roadmap later depends on native capabilities such as:

- richer notifications
- background sync
- stronger device integrations
- app-store-first distribution strategy

### Recommendation

Stay on the web/PWA path for now. Revisit wrappers only when distribution or device-feature requirements change.

## Recommended Order

1. Implement install funnel analytics
2. Improve icon and screenshot assets
3. Re-evaluate web push after analytics data exists
4. Keep offline mode policy unchanged
5. Revisit TWA or native wrapper only if product strategy changes

## Not-Now List

- full offline content sync
- cached read-only quiz catalog
- broad dynamic route caching
- push notifications without user segmentation and consent strategy
- wrapper migration before the web product reaches maturity
