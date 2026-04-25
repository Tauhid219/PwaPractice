# Level CRUD & Seeder Refactor — Implementation Plan

> **আর্কিটেকচার সিদ্ধান্ত (চূড়ান্ত):** Per-Category Levels (বিকল্প B) — প্রতিটি ক্যাটাগরির নিজস্ব ১০টি Level থাকবে।

> **Scope:** দুটি স্বাধীন কিন্তু পরস্পর-সম্পর্কিত কাজ একসাথে করা হবে।
> 1. **Level CRUD** — Admin প্যানেলে Level-এর সম্পূর্ণ Create / Read / Update / Delete ব্যবস্থা।
> 2. **Seeder Refactor** — ৯টি ক্যাটাগরিতে ৩ লেভেল → ১০ লেভেল, প্রতি লেভেলে ১০টি প্রশ্ন।

---

## UI Design Standards (চূড়ান্ত)

> [!IMPORTANT]
> এই প্রজেক্টের সমস্ত UI কাজের জন্য নিচের দুটি টেমপ্লেট **রেফারেন্স হিসেবে** ব্যবহার করতে হবে। কোনো অবস্থাতেই অন্য UI pattern অনুসরণ করা যাবে না।

| Surface | Template | Path |
|---|---|---|
| **Admin Panel** | AdminLTE 3.1.0 | `C:\xampp\htdocs\Templates\AdminLTE-3.1.0` |
| **Frontend (Student-facing)** | Kider 1.0.0 | `C:\xampp\htdocs\Templates\Free.Bundle.2023\Free bundle 2023\46 kider-1.0.0\kider-1.0.0` |

### Admin Panel (AdminLTE 3.1.0) — অনুসরণীয় নিয়ম

- সব admin view `<x-admin-layout>` component ব্যবহার করবে।
- Card: `card card-outline card-primary shadow-sm` class pattern।
- Table: `table table-hover table-striped` class pattern।
- Buttons: `btn btn-sm btn-info` (edit), `btn btn-sm btn-danger` (delete), `btn btn-primary` (add new)।
- Badges: `badge badge-success` / `badge badge-danger` / `badge badge-secondary`।
- Form inputs: `form-control`, `form-group`, `form-check` — Bootstrap 4 convention।
- Flash messages: `@if(session('success'))` / `@if(session('error'))` alert block।
- Permission gate: `@can('create levels')` / `@can('edit levels')` / `@can('delete levels')`।
- AdminLTE template-এর নির্দিষ্ট component দেখতে হলে path: `AdminLTE-3.1.0/index.html` এবং সংশ্লিষ্ট page files।

### Frontend (Kider 1.0.0) — অনুসরণীয় নিয়ম

- এই CRUD-এ কোনো student-facing view নেই। তবে ভবিষ্যতে Level-related frontend কাজ হলে Kider template অনুসরণ করতে হবে।
- Kider template-এর color palette, typography, card style এবং button design frontend-এর জন্য standard।
- Template path: `kider-1.0.0/index.html` এবং সংশ্লিষ্ট inner pages।

---

## পরিস্থিতি বিশ্লেষণ (Current State)

### Level টেবিলের বর্তমান স্কিমা

```sql
levels: id | name | required_score_to_unlock | is_free | timestamps
```

`level_id` কলামটি `questions` টেবিলে FK হিসেবে আছে (`nullable`, `onDelete: set null`)।

### বর্তমান সমস্যা

| সমস্যা | বিবরণ |
|---|---|
| কোনো `category_id` নেই Level-এ | Level এখন global — কোন ক্যাটাগরির Level সেটা বলা যাচ্ছে না |
| Level CRUD নেই | Admin ম্যানুয়ালি seeder দিয়ে Level যোগ করতে বাধ্য |
| Seeder মাত্র ৩টি Level তৈরি করে | কিন্তু প্রশ্নে ১০ লেভেল দরকার |
| question seeder-এ ৯০টি প্রশ্ন commented | ১০ লেভেলে ভাগ করতে হলে এগুলো uncomment করতে হবে |

---

## আর্কিটেকচারাল সিদ্ধান্ত

### সুপারিশ: `category_id` যোগ করা হবে `levels` টেবিলে

**কারণ:**
- এখন একটি Level (যেমন "Level 1") সব ক্যাটাগরির জন্য shared। ফলে ইসলামী জ্ঞানের Level 1 এবং বিজ্ঞানের Level 1 একই entity — যা conceptually ভুল।
- Admin যদি "কুরআনুল কারীম" ক্যাটাগরিতে নতুন লেভেল যোগ করে, তাহলে সেটা যেন অন্য ক্যাটাগরিতে দেখা না যায়।
- `Level` → `Category` belongsTo সম্পর্ক থাকলে, Question assign করার সময় ক্যাটাগরি অনুযায়ী ফিল্টার করা যাবে।

**প্রভাব:**
- `questions` টেবিলে `level_id` FK ঠিকঠাক থাকবে — শুধু Level model-এর scope পরিবর্তন হবে।
- `FrontendController`-এর cache logic-এ Level লোড পদ্ধতি update করতে হবে।

> [!IMPORTANT]
> `category_id` কলামটি `levels` টেবিলে nullable রাখা হবে, যাতে পুরনো migration সম্পর্কযুক্ত data নষ্ট না হয় এবং global level-ও সম্ভব থাকে।

---

## ফেজ ১ — Database Migration [✅ Complete]

### নতুন Migration: `add_category_id_to_levels_table`

**ফাইল:** `database/migrations/2026_04_23_XXXXXX_add_category_id_to_levels_table.php`

```php
Schema::table('levels', function (Blueprint $table) {
    $table->foreignId('category_id')
          ->nullable()
          ->after('name')
          ->constrained('categories')
          ->onDelete('cascade');
    
    $table->integer('order')->default(0)->after('category_id');
});
```

> [!NOTE]
> `onDelete('cascade')` ব্যবহার করা হচ্ছে — কোনো ক্যাটাগরি delete হলে তার Level গুলোও মুছে যাবে। Level মুছে গেলে `questions.level_id` ইতিমধ্যেই `set null` আছে, তাই প্রশ্ন নিরাপদ থাকবে।

---

## ফেজ ২ — Model Update [✅ Complete]

### `app/Models/Level.php` আপডেট

**যোগ করতে হবে:**
- `$fillable`-এ `category_id`, `order` যোগ।
- `category()` belongsTo relationship।
- `booted()` hook যাতে Cache flush হয় (Category model-এর মতো pattern)।
- `$casts` array-তে `is_free` cast।

```php
protected $fillable = ['name', 'category_id', 'order', 'required_score_to_unlock', 'is_free'];

protected static function booted(): void
{
    static::saved(fn () => Cache::flush());
    static::deleted(fn () => Cache::flush());
}

public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}
```

---

## ফেজ ৩ — Form Request Classes [✅ Complete]

### `app/Http/Requests/StoreLevelRequest.php` (নতুন)

| ফিল্ড | Rule |
|---|---|
| `name` | required, string, max:100 |
| `category_id` | required, exists:categories,id |
| `order` | required, integer, min:1, max:100 |
| `required_score_to_unlock` | required, integer, min:0 |
| `is_free` | boolean |

### `app/Http/Requests/UpdateLevelRequest.php` (নতুন)

Same rules, `sometimes` qualifier যোগ করা হবে।

---

## ফেজ ৪ — Admin Controller [✅ Complete]

### `app/Http/Controllers/Admin/LevelController.php` (নতুন)

**Pattern:** `CategoryController`-এর মতো `HasMiddleware` implement করবে।

**Permissions mapping:**

| Method | Required Permission |
|---|---|
| `index`, `show` | `manage levels` |
| `create`, `store` | `create levels` |
| `edit`, `update` | `edit levels` |
| `destroy` | `delete levels` |

**`index()`:** সব level load করবে `with('category')` ও `orderBy('category_id')->orderBy('order')`।

**`create()`:** সব category pass করবে view-এ।

**`store()`:** `StoreLevelRequest` দিয়ে validate, তারপর `Level::create()`।

**`edit()` / `update()`:** `UpdateLevelRequest` দিয়ে validate।

**`destroy()`:** Delete করার আগে check — যদি কোনো Question এই Level-এ assign থাকে, warning redirect।

> [!TIP]
> `destroy()`-এ soft check: `$level->questions()->count() > 0` হলে `back()->with('error', '...')` redirect করা ভালো। DB-তে `set null` আছে তবুও user-কে warn করা UX-এর জন্য ভালো।

---

## ফেজ ৫ — Routes [✅ Complete]

### `routes/web.php`-এ যোগ করতে হবে

```php
Route::resource('levels', Admin\LevelController::class);
```

`admin` prefix group-এর ভেতরে, ঠিক `categories` resource-এর পাশে।

---

## ফেজ ৬ — Permissions Seeder [✅ Complete]

### `database/seeders/RolesAndPermissionsSeeder.php` আপডেট

নতুন permissions যোগ করতে হবে:

```
manage levels
create levels
edit levels
delete levels
```

**এবং** Super Admin / Admin role-এ এই permissions assign করতে হবে।

---

## ফেজ ৭ — Blade Views [✅ Complete]

> [!IMPORTANT]
> সব admin view **AdminLTE 3.1.0** template অনুসরণ করবে।
> Reference: `C:\xampp\htdocs\Templates\AdminLTE-3.1.0`
> বিদ্যমান admin views (`admin/categories/`, `admin/questions/`) দেখে exact pattern বজায় রাখতে হবে।

### ৭.১ `resources/views/admin/levels/index.blade.php`

**Layout:** `<x-admin-layout>` → `card card-outline card-primary shadow-sm` → `table table-hover table-striped`

**Table columns:**

| কলাম | বিবরণ | UI Pattern |
|---|---|---|
| Category | `$level->category->name` | `badge badge-info` |
| Level Name | Bold text | `<strong>` |
| Order | Numeric | `badge badge-secondary` |
| Questions Count | Eager loaded count | Plain text |
| Is Free | Boolean | `badge badge-success` / `badge badge-danger` |
| Actions | CRUD buttons | `btn btn-sm btn-info` / `btn btn-sm btn-danger` |

**Filter bar:** ক্যাটাগরি-ভিত্তিক `<select>` dropdown — GET parameter `?category_id=`। CategoryController-এর question filter pattern অনুসরণ করতে হবে।

**Permission gates:**
```blade
@can('create levels') ... @endcan
@can('edit levels') ... @endcan
@can('delete levels') ... @endcan
```

### ৭.২ `resources/views/admin/levels/create.blade.php`

**Layout:** `<x-admin-layout>` → `card card-outline card-primary`

**Form fields (Bootstrap 4 `form-group` pattern):**

| Field | Type | Note |
|---|---|---|
| Category | `<select class="form-control">` | সব category list, required |
| Level Name | `<input type="text" class="form-control">` | max 100 chars |
| Order | `<input type="number" class="form-control">` | min 1 |
| Required Score | `<input type="number" class="form-control">` | default 0 |
| Is Free | `<input type="checkbox" class="form-check-input">` | checked by default |

**Validation errors:** `@error('field_name')` → `<span class="text-danger">` pattern।

### ৭.৩ `resources/views/admin/levels/edit.blade.php`

Create form-এর মতোই — `old('field', $level->field)` দিয়ে current value pre-populated।

---

## ফেজ ৮ — Admin Sidebar/Menu Update [✅ Complete]

`resources/views/layouts/admin.blade.php` বা sidebar partial-এ Levels menu item যোগ।

**AdminLTE sidebar nav-item pattern:**
```html
<li class="nav-item">
    <a href="{{ route('admin.levels.index') }}" class="nav-link {{ request()->routeIs('admin.levels.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-layer-group"></i>
        <p>Levels</p>
    </a>
</li>
```

Categories menu item-এর নিচে রাখতে হবে (study catalog group-এ)।

---

## ফেজ ৯ — Seeder Refactor [✅ Complete]

### ৯.১ LevelSeeder.php — ভূমিকা পরিবর্তন

**Per-Category কনফার্ম হওয়ায়** `LevelSeeder.php` এখন আর standalone Level তৈরি করবে না।

- **নতুন ভূমিকা:** `LevelSeeder` empty করা হবে বা সম্পূর্ণ সরিয়ে দেওয়া হবে।
- Level তৈরির দায়িত্ব `CategorySeeder`-এর হবে — প্রতিটি ক্যাটাগরি তৈরির সাথে সাথে সেই ক্যাটাগরির ১০টি Level তৈরি হবে।
- `DatabaseSeeder`-এ `LevelSeeder::class` call সরিয়ে দেওয়া হবে (অথবা LevelSeeder-কে শূন্য রাখা হবে)।

**চূড়ান্ত data shape:**

| Category | Levels | Questions |
|---|---|---|
| কুরআনুল কারীম | Level 1–10 (ID: 1–10) | প্রতি লেভেলে ১০টি |
| রাসুল (সা), হাদীস... | Level 1–10 (ID: 11–20) | প্রতি লেভেলে ১০টি |
| বিবিধ ইসলামী জ্ঞান | Level 1–10 (ID: 21–30) | প্রতি লেভেলে ১০টি |
| ... | ... | ... |
| আমার দেশ বাংলাদেশ | Level 1–10 (ID: 81–90) | প্রতি লেভেলে ১০টি |

**মোট:** ৯০ Level row, ৯০০ Question row (৯ ক্যাটাগরি × ১০০ প্রশ্ন)।

### ৯.২ Question Seeder Files — Uncomment ও Relabel Strategy

**বর্তমান structure প্রতিটি ফাইলে:**
```
Level 1: 30 প্রশ্ন (10 active + 20 commented)  [array index: 1]
Level 2: 30 প্রশ্ন (10 active + 20 commented)  [array index: 2]
Level 3: 40 প্রশ্ন (10 active + 30 commented)  [array index: 3]
মোট: 100 প্রশ্ন, ৩০টি active
```

**নতুন target:**
```
Level 1–10: প্রতিটিতে 10 প্রশ্ন  [array index: 1–10]
মোট: 100 প্রশ্ন, সবগুলো active (কোনো comment নেই)
```

**Mapping — পুরনো → নতুন level number:**

| পুরনো প্রশ্ন গ্রুপ | প্রশ্ন নম্বর (ফাইলে) | নতুন Level |
|---|---|---|
| Level 1 — ১ম ব্লক (active 1–10) | Q 1–10 | **Level 1** |
| Level 1 — ২য় ব্লক (commented 11–20) | Q 11–20 | **Level 2** |
| Level 1 — ৩য় ব্লক (commented 21–30) | Q 21–30 | **Level 3** |
| Level 2 — ১ম ব্লক (active 31–40) | Q 31–40 | **Level 4** |
| Level 2 — ২য় ব্লক (commented 41–50) | Q 41–50 | **Level 5** |
| Level 2 — ৩য় ব্লক (commented 51–60) | Q 51–60 | **Level 6** |
| Level 3 — ১ম ব্লক (active 61–70) | Q 61–70 | **Level 7** |
| Level 3 — ২য় ব্লক (commented 71–80) | Q 71–80 | **Level 8** |
| Level 3 — ৩য় ব্লক (commented 81–90) | Q 81–90 | **Level 9** |
| Level 3 — ৪র্থ ব্লক (commented 91–100) | Q 91–100 | **Level 10** |

> [!NOTE]
> প্রতিটি question seeder ফাইলের header comment আপডেট হবে:
> `// Level 1: 30 | Level 2: 30 | Level 3: 40` → `// Level 1–10: প্রতিটিতে ১০টি প্রশ্ন`

**৯টি ফাইল আপডেট হবে:**
1. `QuranulKareemQuestions.php`
2. `RasulHadithSahabaQuestions.php`
3. `IslamicKnowledgeQuestions.php`
4. `HistoryHeritageQuestions.php`
5. `EducationLiteratureCultureQuestions.php`
6. `SportsQuestions.php`
7. `ScienceTechGeographyQuestions.php`
8. `AstonishingWorldQuestions.php`
9. `BangladeshQuestions.php`

### ৯.৩ CategorySeeder.php — সম্পূর্ণ নতুন Flow

**নতুন flow:**

```php
use App\Models\Level;

foreach ($categories as $index => $catData) {
    $questionFile = $catData['file'];
    unset($catData['file']);
    $catData['order'] = $index + 1;
    $catData['description'] = $catData['name'] . ' বিষয়ক প্রশ্ন ও উত্তর।';

    // ১. Category তৈরি
    $category = Category::create($catData);

    // ২. এই ক্যাটাগরির জন্য ১০টি Level তৈরি
    $levels = [];
    for ($i = 1; $i <= 10; $i++) {
        $levels[$i] = Level::create([
            'name'                     => 'Level ' . $i,
            'category_id'              => $category->id,
            'order'                    => $i,
            'required_score_to_unlock' => ($i - 1) * 10,
            'is_free'                  => $i === 1,
        ]);
    }

    // ৩. Question load ও assign
    $questions = require __DIR__ . '/questions/' . $questionFile;

    foreach ($questions as $qData) {
        $options = [$qData[2], $qData[3], $qData[4]];
        shuffle($options);

        Question::create([
            'category_id'   => $category->id,
            'level_id'      => $levels[$qData[0]]->id,  // $qData[0] = 1–10
            'question_text' => $qData[1],
            'option_1'      => $options[0],
            'option_2'      => $options[1],
            'option_3'      => $options[2],
            'answer_text'   => $qData[5],
        ]);
    }
}
```

> [!NOTE]
> `LevelSeeder` আর independently call হবে না। `DatabaseSeeder`-এ `LevelSeeder::class` line সরিয়ে দেওয়া হবে। Level তৈরির পুরো দায়িত্ব `CategorySeeder`-এর।

---

## ফেজ ১০ — Cache Invalidation Review [✅ Complete]

`FrontendController`-এ যেখানে categories ও levels load হয়, সেখানে eager loading review করতে হবে। Level model-এ `booted()` hook থাকলে cache auto-flush হবে।

---

## Execution Order

```
Migration → Model → Form Requests → Controller → Routes
→ Permissions Seeder → Blade Views → Sidebar Menu
→ Question Seeders (uncomment + relabel)
→ LevelSeeder update → CategorySeeder update
→ migrate:fresh --seed → Manual test
```

---

## ফাইল চেকলিস্ট

| ফাইল | Action |
|---|---|
| `database/migrations/..._add_category_id_to_levels_table.php` | New |
| `app/Models/Level.php` | Edit |
| `app/Http/Requests/StoreLevelRequest.php` | New |
| `app/Http/Requests/UpdateLevelRequest.php` | New |
| `app/Http/Controllers/Admin/LevelController.php` | New |
| `routes/web.php` | Edit |
| `database/seeders/RolesAndPermissionsSeeder.php` | Edit |
| `resources/views/admin/levels/index.blade.php` | New |
| `resources/views/admin/levels/create.blade.php` | New |
| `resources/views/admin/levels/edit.blade.php` | New |
| `resources/views/layouts/admin.blade.php` (sidebar) | Edit |
| `database/seeders/LevelSeeder.php` | Edit |
| `database/seeders/CategorySeeder.php` | Edit |
| `database/seeders/questions/*.php` (৯টি) | Edit |

---

## ঝুঁকি ও সতর্কতা

> [!WARNING]
> `LevelSeeder` এবং `CategorySeeder` পরিবর্তনের পর অবশ্যই `migrate:fresh --seed` চালাতে হবে। Live data থাকলে মনে রেখো — **fresh migration সব data মুছে দেবে।**

> [!CAUTION]
> `FrontendController`-এ Level data cache হয়। Migration চালানোর পর `php artisan cache:clear` না চালালে ফ্রন্টএন্ড পুরনো data দেখাতে পারে।

> [!NOTE]
> Question seeder-এ level number relabeling করার সময় সতর্ক থাকতে হবে — Level 1-এর ১০টি, Level 2-এর ১০টি এভাবে পরিষ্কারভাবে ভাগ করতে হবে। Per-category Level হলে, CategorySeeder-এ level_id lookup সঠিকভাবে করতে হবে।

---

*পরিকল্পনা প্রস্তুত: 2026-04-23*
