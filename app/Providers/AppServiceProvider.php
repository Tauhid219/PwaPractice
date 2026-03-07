<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('frontend.*', function ($view) {
            $view->with('globalCategories', \App\Models\Category::orderBy('order')->get());
        });
    }
}
