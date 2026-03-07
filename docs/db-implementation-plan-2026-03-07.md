# Database Implementation Plan for Level System & Monetization

**তারিখ:** ০৭ মার্চ ২০২৬

This plan outlines the database changes required to support the new features requested by the client: level-based learning, live exams, and app payment constraints.

## Goal Description
We need to introduce a Level system where questions belong to specific levels. Users must complete a level to unlock the next one. We also need to track user progress (which levels are unlocked/completed) and quiz attempts (pass/fail status). Finally, questions will be updated to attach to a `level_id`.

## Proposed Changes

### Database Migrations
---
We will create three new migrations and update one existing migration (or create a new migration to alter the `questions` table).

#### `create_levels_table.php` (Migration)
- `id` (Primary Key)
- `name` (e.g., "Level 1", "Beginner")
- `required_score_to_unlock` (Integer - How much score is needed to unlock this level. 0 for the first level)
- `is_free` (Boolean - True for the first level to act as a free trial)
- `timestamps`

#### `create_user_progress_table.php` (Migration)
- `id` (Primary Key)
- `user_id` (Foreign Key -> users)
- `level_id` (Foreign Key -> levels)
- `status` (Enum/String: 'locked', 'active', 'completed')
- `timestamps`

#### `create_quiz_attempts_table.php` (Migration)
- `id` (Primary Key)
- `user_id` (Foreign Key -> users)
- `level_id` (Foreign Key -> levels)
- `score` (Integer)
- `passed` (Boolean)
- `timestamps`

#### `add_level_id_to_questions_table.php` (Migration)
- Add `level_id` (Foreign Key -> levels, nullable, constrained) to the `questions` table.

### Models
---
Create and update Eloquent Models to represent the relationships.

#### `app/Models/Level.php`
- Add fillable properties: `name`, `required_score_to_unlock`, `is_free`.
- Add relationships: `hasMany(Question::class)`, `hasMany(UserProgress::class)`, `hasMany(QuizAttempt::class)`.

#### `app/Models/UserProgress.php`
- Add fillable properties: `user_id`, `level_id`, `status`.
- Add relationships: `belongsTo(User::class)`, `belongsTo(Level::class)`.

#### `app/Models/QuizAttempt.php`
- Add fillable properties: `user_id`, `level_id`, `score`, `passed`.
- Add relationships: `belongsTo(User::class)`, `belongsTo(Level::class)`.

#### `app/Models/Question.php`
- Add `level_id` to fillable properties.
- Add relationship: `belongsTo(Level::class)`.

#### `app/Models/User.php`
- Add relationships: `hasMany(UserProgress::class)`, `hasMany(QuizAttempt::class)`.
