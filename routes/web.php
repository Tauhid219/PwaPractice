<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Frontend\QuizController;
use App\Http\Controllers\Frontend\LiveExamController;

// Frontend Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/category/{slug}', [FrontendController::class, 'categoryLevels'])->name('category.levels');
Route::get('/category/{slug}/level/{level}', [FrontendController::class, 'levelQuestions'])->name('level.questions');
Route::view('/offline', 'offline')->name('offline');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quiz Routes
    Route::middleware(['check.level.access'])->group(function () {
        Route::get('/category/{category:slug}/level/{level}/quiz', [QuizController::class, 'start'])->name('quiz.start');
        Route::post('/category/{category:slug}/level/{level}/quiz', [QuizController::class, 'submit'])->name('quiz.submit');
    });
    Route::get('/quiz/attempt/{attempt}/result', [QuizController::class, 'result'])->name('quiz.result');

    // Live Exam Routes
    Route::get('/live-exams', [LiveExamController::class, 'index'])->name('live-exams.index');
    Route::get('/live-exams/{exam}', [LiveExamController::class, 'show'])->name('live-exams.show');
    Route::get('/live-exams/{exam}/join', [LiveExamController::class, 'join'])->name('live-exams.join');
    Route::post('/live-exams/{exam}/submit', [LiveExamController::class, 'submit'])->name('live-exams.submit');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::post('questions/import', [QuestionController::class, 'import'])->name('questions.import');
    Route::resource('questions', QuestionController::class);
    Route::resource('users', UserController::class)->only(['index', 'update']);
});

require __DIR__.'/auth.php';
