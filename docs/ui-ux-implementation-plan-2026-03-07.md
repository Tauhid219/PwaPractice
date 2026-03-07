# Implementation Plan for Professional UI/UX (Sidebar, Bottom Nav & Audio)

**তারিখ:** ০৭ মার্চ ২০২৬

This plan outlines the changes needed to address **Phase 3** of the roadmap: introducing a sticky category sidebar for desktop, a bottom navigation bar for mobile, and interaction sounds (Pop/Click).

## Goal Description
The client requested a more professional and aesthetic layout. 
1. **Desktop view**: We will add a fixed/sticky sidebar on the left side that lists all the categories. This allows users to jump between subjects quickly without going back to the homepage.
2. **Mobile view**: Since a sidebar takes up too much screen space, we will hide the sidebar on mobile and instead add a fixed Bottom Navigation Bar or a horizontal scrollable category bar. 
3. **UI Interaction Sound**: Clicking buttons, expanding accordions, or selecting options will trigger an audible 'Pop' or 'Click' sound to enhance the feel of the Progressive Web App (PWA).

## Proposed Changes

### 1. Global Layout & Sidebar Integration
We will update the master layout to use a row with a sidebar column and a main content column on desktop screens.

#### `resources/views/frontend/layouts/master.blade.php`
- Wrap `@yield('content')` inside a wrapper div with `row`. 
- Include a new sidebar component `<aside class="col-lg-3 d-none d-lg-block sticky-top">...` on the left.
- Modify the main content container to take `col-lg-9`.
- Add script block at the very bottom to handle the Sound Effects.

#### `resources/views/frontend/layouts/sidebar.blade.php`
- A new file displaying the Categories in a vertical list.
- We will fetch `$globalCategories` via a View Composer or directly inject them in `AppServiceProvider` so the sidebar has data on every page.

### 2. Mobile Bottom Navigation
Since the sidebar is hidden on mobile (`d-none d-lg-block`), we need a mobile-friendly alternative.

#### `resources/views/frontend/layouts/bottom_nav.blade.php`
- A fixed bottom bar (`fixed-bottom`) visible only on mobile (`d-lg-none`).
- Will contain icons for 'Home', 'Categories' (triggers Offcanvas menu or goes to home), and 'Profile/Logout'.

### 3. Audio UI Interaction
We will add standard HTML5 `<audio>` tags for interaction sounds. 

#### `public/frontend/sounds/click.mp3` and `public/frontend/sounds/pop.mp3`
- We need basic audio files. 

#### `public/frontend/js/main.js` (or inline script)
- A global JavaScript listener on `.btn`, `a`, and `.accordion-button` classes.
- Play the associated `mp3` file on `mousedown` or `click`.
