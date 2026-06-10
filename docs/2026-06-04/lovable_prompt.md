# 🎨 Lovable Prompt: Genius Kids Quiz Frontend (Kids-Friendly UI)

This document contains a comprehensive, production-ready prompt designed to be copy-pasted directly into **Lovable (lovable.dev)** to generate a vibrant, gamified, and highly interactive kids-friendly frontend for the "Genius Kids - Quiz Guidebook" app.

---

## 📝 copy-paste-ready Lovable Prompt

Copy the entire block below and paste it into Lovable's prompt box:

```text
Create a complete, single-page interactive prototype (or multi-view application) for a kids-friendly educational quiz web app named "Genius Kids - Quiz Guidebook". 
Technology stack: Plain HTML, CSS, Tailwind CSS, FontAwesome icons, and Vanilla JavaScript (No React, No Vue, No React Icons). Ensure all styles use standard Tailwind utility classes. Make it fully responsive (optimized for mobile phones and tablets, as it will be used as a PWA).

### 🎨 DESIGN SYSTEM & VISUAL THEME:
- Theme: "Duolingo meets Nintendo". Super playful, modern, gamified, and friendly.
- Typography: Use Google Fonts "Nunito" or "Quicksand" (soft, rounded, highly readable sans-serif).
- Colors: Soft, vibrant pastel-based palette:
  * Primary/Sky Blue (sky-400, sky-500)
  * Secondary/Grass Green (emerald-400, emerald-500)
  * Accent/Sunny Yellow (amber-300, amber-400)
  * Playful Orange (orange-400, orange-500)
  * Coral Red/Danger (rose-400, rose-500)
- Styling Details:
  * Large rounded corners ("rounded-2xl" and "rounded-3xl").
  * Neobrutalism/Playful borders: Thick offset shadows like "shadow-[4px_4px_0px_#000000]" or "shadow-[0px_8px_0px_#E2E8F0]" (Duolingo-like 3D borders for buttons).
  * Smooth bounce hover animations ("hover:-translate-y-1 active:translate-y-0 transition-all").
  * Cute emojis and illustrations.
- Bangla Copy: Use kid-friendly Bangla text for headers and buttons (e.g., "কুইজ শুরু করো!", "হোম", "পরের প্রশ্ন", "দারুণ হয়েছে!").

### 📱 VIEWS / SCREENS TO INCLUDE:
Create a navigation bar or dashboard menu at the bottom (for mobile) and top (for desktop) that lets users switch between these views dynamically via simple Vanilla JS state switching:

1. 🏠 HOME VIEW (Category Grid):
   - Header showing student name, avatar (cute cartoon animal), and a streak counter (e.g., "🔥 ৫ দিনের ধারা").
   - A grid of 9 colorful subject cards: Qur'an (কোরআন), Prophet PBUH (মহানবী সা.), Hadith and Sahaba (হাদিস ও সাহাবা), Islamic Knowledge (ইসলামিক জ্ঞান), History & Heritage (ইতিহাস ও ঐতিহ্য), Education & Literature (শিক্ষা ও সাহিত্য), Sports (খেলাধুলা), Science & Geography (বিজ্ঞান ও ভূগোল), World Knowledge (বিশ্ব পরিচিতি), Bangladesh (বাংলাদেশ).
   - Each card should have a solid accent color border, a fun icon/emoji, a progress bar (e.g., "৩/১০ লেভেল"), and a button.

2. 🗺️ LEVEL MAP VIEW (Duolingo-style Path):
   - A vertical curved path (dotted/dashed line) connecting numbered circular level nodes (Level 1 to Level 10).
   - Level 1: "Active/Unlocked" node - glowing green, jumping/pulsing animation, showing a checkmark or play icon.
   - Level 2 to 10: "Locked" nodes - grayscale/grey, with padlock icons.
   - Clicking the active level node opens a cute popup card with two choices: "১. পড়তে চাই (Study)" and "২. কুইজ খেলি (Quiz)".

3. 📖 STUDY VIEW (Flashcard/Accordion Q&A):
   - A page containing 10 study questions.
   - Design: Display questions as a deck of cards or Accordion bars. Clicking a question reveals the answer with a playful animation.
   - Include a "শোনাও (Read Aloud)" button next to each question (visual speaker icon).
   - A big bright button at the bottom: "পরীক্ষা দাও / কুইজ শুরু করো! (Start Quiz)" with a bouncy arrow.

4. 🎮 PLAYFUL QUIZ VIEW (Interactive MCQ):
   - Header displaying a progress tracker ("প্রশ্ন ২ / ১০"), a vibrant progress bar (sky-400), and a timer.
   - A large, soft container card for the question text.
   - 3 large options buttons (ক, খ, গ) with 3D board styles.
   - **Interactive JS Logic**:
     * When a student clicks an option, check its correctness immediately.
     * If CORRECT: The clicked button turns bright green (bg-emerald-500 text-white), plays a bounce animation, and displays a "🎉 দারুণ!" checkmark.
     * If INCORRECT: The clicked button turns red (bg-rose-500 text-white), shakes side-to-side, and the correct button is highlighted in green.
     * Disable other buttons after selection.
     * Show a bottom action banner with the feedback: if correct, show green bar with "অসাধারণ হয়েছে!", if incorrect, show red bar with "সঠিক উত্তরটি দেখো...".
     * A "পরের প্রশ্ন (Next)" button to transition to the next question.

5. 🏆 QUIZ RESULT VIEW:
   - Full screen confetti animation (if score is >= 80%).
   - Display a giant glowing gold trophy emoji and a message: "অভিনন্দন! তুমি পাস করেছো!" or "ইশ! আরেকটু চেষ্টা করো!"
   - Fun statistics dashboard showing:
     * Score: e.g., "৮ / ১০ সঠিক" (colored green/red).
     * Earned XP: "+৫০ এক্সপি (XP)".
     * Total Time taken.
   - Navigation buttons: "আবার পরীক্ষা দাও (Retry)" and "পরবর্তী লেভেল আনলক করো (Next Level)" in neon-green color.

6. 🎖️ PROFILE & PROGRESS VIEW (Gamified Dashboard):
   - Large profile banner with a customizable cartoon avatar (let kids click to change avatars: Panda, Lion, Cat, Rabbit).
   - A grid of unlocked "সাফল্যের ব্যাজ (Achievements/Badges)":
     * "🔥 ৩ দিনের ধারা" (Daily streak) - unlocked.
     * "🎯 প্রথম কুইজ জয়" (First quiz pass) - unlocked.
     * "💯 শতক কুইজ" (100 Quizzes) - locked/greyed out.
     * "👑 লেভেল মাস্টার" (Finish 10 levels) - locked/greyed out.
   - Stats summary: Total Quizzes played, Streak count, and Total XP points.

7. ⏱️ LIVE EXAM VIEW:
   - Clean, focused interface with a top sticky warning header: a big countdown clock and an icon showing "পরীক্ষা চলছে (Exam in Progress)".
   - Anti-cheat warning indicator: "ট্যাব পরিবর্তন করলে পরীক্ষা বাতিল হবে!".
   - Simple 3-option MCQs.
   - Scoreboard/Leaderboard view at the end with ranks (1st 🏆, 2nd 🥈, 3rd 🥉) showing user avatars and usernames.

8. 🔑 AUTHENTICATION VIEW (Register/Login):
   - Very clean, child-friendly form fields with cartoon icons.
   - Login / Signup switcher.
   - Colorful Google Login button ("গুগল দিয়ে লগইন করো").

### ⚡ INTERACTIVE JAVASCRIPT DETAILS:
- Write clean, vanilla JS inside a script tag to manage state switches between these views so that the user can fully test the flow (Home -> Level Map -> Popups -> Study -> Quiz -> Result -> Profile).
- Simulate sound effects with standard HTML5 Web Audio API synths (beeps and chime tones for correct/incorrect) so the app makes playful sounds during button clicks and quiz answers without needing external mp3 files.
- Include dummy data for the 9 categories and 10 levels to make the mockup look complete and functional.
```

---

## 🛠️ How to use this in Lovable:
1. Open [Lovable.dev](https://lovable.dev/).
2. Create a new project.
3. Paste the prompt block above directly into the input chat.
4. Let Lovable generate the design, and you will get a beautiful, kid-friendly frontend mockup with Tailwind.
5. Once generated, you can export the HTML/CSS/Tailwind structure and slice it directly into your Laravel Blade templates inside the `resources/views/frontend/` folder.
