<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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
        Paginator::useBootstrapFive();

        View::composer('frontend.*', function ($view) {
            static $categories;
            if (!$categories) {
                $categories = \Illuminate\Support\Facades\Cache::remember('global_categories_all', 3600, function () {
                    return Category::orderBy('order')->get();
                });
            }
            $view->with('globalCategories', $categories);
        });
    }
}
