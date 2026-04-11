# Student and Admin Login Separation Plan

## Objective
To implement separate login experiences for Students and Admins. Students will use a visually appealing frontend login page based on the "Kider" template, while Admins will use a hidden route to access the existing backend login page.

## 1. Route Restructuring
We need to separate the routes for Admin and Student logins.
- **Student Login Route:** Keep the default `/login` route for students. A "Login" button will be placed on the frontend navbar that redirects here.
- **Admin Login Route:** Create a new custom route for the admin login, for example, `/admin-access` or `/secret-login`. Admins will need to manually type this URL to reach their login panel.

## 2. Views and Design
- **Admin Login View:** The existing login view (`resources/views/auth/login.blade.php`, assuming it's the AdminLTE/Breeze one) will be moved and configured to only show up for the new admin route. 
- **Student Login View:** A new Blade view will be created (e.g., `resources/views/frontend/auth/login.blade.php`). The design and necessary CSS/JS assets will be extracted from the specified Kider template:
  `C:\xampp\htdocs\Templates\Free.Bundle.2023\Free bundle 2023\46 kider-1.0.0\kider-1.0.0`
  (We will adapt a page from this template to serve as the student login interface).

## 3. Authentication Logic & Redirection
- **Middleware & Redirects:** Ensure that if a guest tries to access an auth-protected route, they are redirected to the appropriate login page based on what they were trying to access, or default to the student login.
- **Post-Login Redirection:** Verify that once a user logs in, the system checks their role. 
  - If the user has an `admin` or `super-admin` role, redirect to the Admin Dashboard.
  - If the user has a `student` role, redirect to the Frontend / Student Profile page.
- **Controllers:** Adjust the `AuthenticatedSessionController` (or Fortify configuration) to handle the multi-login view returns depending on the route hit.

## 4. Implementation Steps
1. **Analyze Kider Template:** Open the Kider template directory to find the best layout for the student login page. Grab the required CSS/JS and HTML structure.
2. **Update Routes:** Modify `routes/web.php` and `routes/auth.php` to define `/login` (Student) and `/admin-portal-login` (Admin).
3. **Create Controllers/Methods:** Update the login view logic to serve the Kider-based view for `/login` and the existing view for `/admin-portal-login`.
4. **Update Frontend UI:** Add the 'Login' button to the Kider template frontend navbar (`resources/views/frontend/layouts/navbar.blade.php`).
5. **Test Scenarios:**
   - Access `/login` -> see Kider student login.
   - Access `/admin-portal-login` -> see existing admin login.
   - Login as student -> redirected to profile.
   - Login as admin -> redirected to dashboard.
   - Admin trying to login via student portal -> properly handled.
