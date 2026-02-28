<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

Route::controller(FrontendController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/category/{slug}', 'categoryChapters')->name('frontend.chapters');
    Route::get('/chapter/{slug}', 'chapterQuestions')->name('frontend.questions');

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
