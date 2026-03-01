<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

Route::controller(FrontendController::class)->group(function () {
    Route::get('/', [FrontendController::class , 'index'])->name('home');
    Route::get('/category/{slug}', [FrontendController::class , 'categoryChapters'])->name('category.chapters');
    Route::get('/chapter/{slug}', [FrontendController::class , 'chapterQuestions'])->name('chapter.questions');
    Route::get('/offline-urls', [FrontendController::class , 'getOfflineUrls']);

    Route::get('/about', 'about')->name('about');
    Route::get('/classes', 'classes')->name('classes');
    Route::get('/facility', 'facility')->name('facility');
    Route::get('/team', 'team')->name('team');
    Route::get('/call-to-action', 'callToActionPage')->name('call-to-action');
    Route::get('/appointment', 'appointment')->name('appointment');
    Route::get('/testimonial', 'testimonial')->name('testimonial');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/404', 'notFound')->name('404');
});
