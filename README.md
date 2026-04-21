# 🧠 Genius Kids — Professional PWA Quiz App

> **Genius Kids** is a modern, high-performance, and feature-rich quiz application built with Laravel 12. It features a professional PWA setup for offline learning, a robust RBAC system, and a specialized administrative panel for live exams and analytics.

---

## 🚀 Key Features

### 👨‍🎓 Student Experience
- **Interactive Quiz Engine:** Level-based learning with instant feedback sounds.
- **Live Exams:** Real-time synchronized exams with anti-cheat measures (tab tracking).
- **Gamification:** Daily streaks (🔥), achievement badges, and progress tracking.
- **PWA Ready:** Installable on Android/iOS/Desktop with offline fallback support.
- **Rich UI:** Smooth page transitions, loading skeletons, and dark mode support.

### 🛠️ Administrative Power
- **Analytics Dashboard:** Chart.js integration for tracking user engagement and quiz performance.
- **Advanced RBAC:** Granular permissions management using Spatie Laravel-Permission.
- **Live Exam Management:** Create timed exams, assign questions, and monitor real-time results.
- **Data Import/Export:** Effortless question management via Excel/CSV imports.
- **Observability:** Integrated Laravel Telescope for debugging and performance monitoring.

---

## 🛠️ Tech Stack
- **Framework:** Laravel 12.x (PHP 8.2+)
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5 (Student UI), AdminLTE 3.1.0 (Admin UI)
- **Database:** MySQL / SQLite
- **Caching & Performance:** Laravel Cache, Redis support, Web Workers for timers.
- **PWA:** Service Workers, Web App Manifest.

---

## 💻 Local Setup Guide

### 1. Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- A database (MySQL/PostgreSQL/SQLite)

### 2. Installation
```bash
# Clone the repository
git clone <repository-url>

# Navigate to the project directory
cd PwaPractice

# Run initial setup script
composer run setup
```

### 3. Development
```bash
# Start development servers (Server + Vite)
composer run dev
```

### 4. Default Credentials
- **Super Admin:** `admin@example.com` / `password`
- **Student Profile:** Create a new account via the registration page.

---

## 🧪 Testing & Quality
- **Test Suite:** Automated tests available via `php artisan test`.
- **Code Quality:** Adheres to PSR-12 standards using Laravel Pint.
- **Linting:** Run `vendor/bin/pint` for auto-formatting.

---

## 📄 License
The Genius Kids project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
