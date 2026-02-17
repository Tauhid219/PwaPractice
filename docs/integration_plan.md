# Laravel Blade Template Integration Plan for "Kider"

This plan outlines the steps to integrate the "Kider" Bootstrap template into the Laravel application.

## 1. Asset Migration
- Create a directory `public/frontend` to keep theme assets organized.
- Copy the following folders from `kider-1.0.0` to `public/frontend`:
  - `css`
  - `js`
  - `img`
  - `lib`
  - `scss` (optional, if we plan to recompile sass, otherwise just keeping it is fine)

## 2. Layout Structure Setup
- Create a folder `resources/views/frontend/layouts`.
- Create a master layout file `resources/views/frontend/layouts/master.blade.php`.
- Extract common HTML parts into partials (optional but recommended for cleanliness):
  - `resources/views/frontend/layouts/header.blade.php` (Head section + Navbar)
  - `resources/views/frontend/layouts/footer.blade.php` (Footer section + Scripts)
- The `master.blade.php` will:
  - Include the header/navbar.
  - Define a `@yield('content')` section.
  - Include the footer.

## 3. View Conversion
- **Home Page**:
  - Create `resources/views/frontend/index.blade.php`.
  - Extend `frontend.layouts.master`.
  - Copy the main content from `index.html` (Carousel, Facilities, About, etc.) into the `@section('content')`.
- **Other Pages**:
  - Create views for other pages (e.g., `about.blade.php`, `classes.blade.php`, `contact.blade.php`, etc.).
  - Copy respective content from the HTML files.

## 4. Route Definition
- Open `routes/web.php`.
- Define routes for each page:
  - `/` -> returns `frontend.index`
  - `/about` -> returns `frontend.about`
  - `/classes` -> returns `frontend.classes`
  - `/contact` -> returns `frontend.contact`
  - ...and so on for other pages.

## 5. Asset Linking
- Update all static links in the blade files to use Laravel's `asset()` helper.
  - CSS: `<link href="css/style.css">` -> `<link href="{{ asset('frontend/css/style.css') }}">`
  - JS: `<script src="js/main.js"></script>` -> `<script src="{{ asset('frontend/js/main.js') }}"></script>`
  - Images: `<img src="img/carousel-1.jpg">` -> `<img src="{{ asset('frontend/img/carousel-1.jpg') }}">`

## 6. Controller Refactor (New)
- Create a Controller `FrontendController`.
- Move route closures logic into controller methods:
  - `index()`
  - `about()`
  - `classes()`
  - `facility()`
  - `team()`
  - `callAction()`
  - `appointment()`
  - `testimonial()`
  - `contact()`
  - `notFound()`
- Update `routes/web.php` to use `FrontendController`.

## 7. Verification
- Visit the site in the browser to ensure specific pages load correctly.
- Check console for any 404 errors regarding assets.
