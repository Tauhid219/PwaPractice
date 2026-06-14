# Implementation Plan: Seeder Level Reduction and Spelling Variations

This plan details the changes required to reduce the number of levels from 10 to 5 for each category, discard questions for levels 6 to 10 in the question seeders, and add 3 to 4 spelling variations/alternative answers for each question's correct answer to support typed quiz verification.

---

## User Review Required

> [!IMPORTANT]
> - **Level Reduction to 5:** We will change `CategorySeeder.php` to only seed 5 levels per category (instead of 10). Questions belonging to levels 6 to 10 will be completely removed from the database seeders.
> - **Spelling Variations:** The correct answers (index 5) in each question array will be expanded using alternative spelling variations separated by the pipe character (`|`), supporting the dynamic quiz typing evaluation.
> - **Programmatic Generation & Review:** We will run a script to automatically extract levels 1-5 and generate spelling variations (e.g. adding English synonyms, phonetic/transliteration mappings, digit translations, and prefix/suffix cleanups). Then we will verify the seeded database.

---

## Open Questions

There are no major open questions, as the user requested specific numbers: exactly 5 levels per category, and 3-4 spelling variations for each question.

---

## Proposed Changes

### Seeders
#### [MODIFY] [CategorySeeder.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/CategorySeeder.php)
- Change level creation loop to generate exactly 5 levels per category (order 1 to 5).

#### [MODIFY] [AstonishingWorldQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/AstonishingWorldQuestions.php)
#### [MODIFY] [BangladeshQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/BangladeshQuestions.php)
#### [MODIFY] [EducationLiteratureCultureQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/EducationLiteratureCultureQuestions.php)
#### [MODIFY] [HistoryHeritageQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/HistoryHeritageQuestions.php)
#### [MODIFY] [IslamicKnowledgeQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/IslamicKnowledgeQuestions.php)
#### [MODIFY] [QuranulKareemQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/QuranulKareemQuestions.php)
#### [MODIFY] [RasulHadithSahabaQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/RasulHadithSahabaQuestions.php)
#### [MODIFY] [ScienceTechGeographyQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/ScienceTechGeographyQuestions.php)
#### [MODIFY] [SportsQuestions.php](file:///c:/xampp/htdocs/Laravel-Practice/PwaPractice/database/seeders/questions/SportsQuestions.php)

- In each question seeder file:
  - Discard questions for levels 6 to 10.
  - Modify the 6th element of each question array to contain alternative spellings separated by `|`.

---

## Verification Plan

### Automated Tests
- Reset database and re-seed:
  - `php artisan migrate:fresh --seed`
- Run the test suite:
  - `php artisan test`

### Manual Verification
- Check levels display on the frontend category levels page to ensure only Levels 1 to 5 are visible and playable.
- Play a quiz level, type spelling variations (e.g. `"blue whale"`, `"নীলতিমি"`, `"nil timi"` for `"নীলতিমি"`) and verify that they are accepted as correct.
