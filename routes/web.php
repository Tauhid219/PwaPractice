<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\LiveExamController as AdminLiveExamController;
use App\Http\Controllers\Frontend\LiveExamController;
use App\Http\Controllers\Frontend\QuizController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Frontend & Study Progress Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/category/{slug}', [FrontendController::class, 'categoryLevels'])->name('category.levels');
Route::get('/category/{slug}/level/{level}', [FrontendController::class, 'levelQuestions'])->name('level.questions');
Route::view('/offline', 'offline')->name('offline');
Route::post('/mark-read', [FrontendController::class, 'markQuestionAsRead'])->name('mark.read');

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole(['super-admin', 'admin', 'moderator', 'editor'])) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/progress', [ProfileController::class, 'progress'])->name('profile.progress');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quiz Routes
    Route::middleware(['check.level.access'])->group(function () {
        Route::get('/category/{slug}/level/{level}/quiz', [QuizController::class, 'start'])->name('quiz.start');
        Route::post('/category/{slug}/level/{level}/quiz', [QuizController::class, 'submit'])->name('quiz.submit');
    });
    Route::get('/quiz/attempt/{attempt}/result', [QuizController::class, 'result'])->name('quiz.result');

    // Live Exam Routes
    Route::get('/live-exams', [LiveExamController::class, 'index'])->name('live-exams.index');
    Route::get('/live-exams/{exam}', [LiveExamController::class, 'show'])->name('live-exams.show');
    Route::get('/live-exams/{exam}/join', [LiveExamController::class, 'join'])->name('live-exams.join');
    Route::post('/live-exams/{exam}/submit', [LiveExamController::class, 'submit'])->name('live-exams.submit');
    Route::get('/live-exams/{exam}/results', [LiveExamController::class, 'results'])->name('live-exams.results');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard')->middleware('permission:access dashboard');

    // User Management
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Questions
    Route::resource('questions', QuestionController::class);
    Route::post('questions/import', [QuestionController::class, 'import'])->name('questions.import');

    // Live Exams
    Route::resource('live-exams', AdminLiveExamController::class);
    Route::get('live-exams/{live_exam}/questions', [AdminLiveExamController::class, 'manageQuestions'])->name('live-exams.questions.manage');
    Route::post('live-exams/{live_exam}/questions', [AdminLiveExamController::class, 'updateQuestions'])->name('live-exams.questions.update');
    Route::get('live-exams/{live_exam}/results', [AdminLiveExamController::class, 'results'])->name('live-exams.results');
});

require __DIR__.'/auth.php';
