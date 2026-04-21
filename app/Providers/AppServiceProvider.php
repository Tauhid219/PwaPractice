<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
            $this->app->register(\App\Providers\TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        $this->configureRateLimiting();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        View::composer('frontend.*', function ($view) {
            static $categories;
            if (! $categories) {
                $categories = Cache::remember('global_categories_all', 3600, function () {
                    return Category::orderBy('order')->get();
                });
            }
            $view->with('globalCategories', $categories);
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('exam-submit', function (Request $request) {
            return Limit::perMinute(1)->by($request->user()?->id ?: $request->ip())->response(function (Request $request, array $headers) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'আপনি খুব দ্রুত রিকোয়েস্ট করছেন। অনুগ্রহ করে ১ মিনিট অপেক্ষা করুন।'], 429, $headers);
                }

                return back()->with('error', 'আপনি খুব দ্রুত রিকোয়েস্ট করছেন। অনুগ্রহ করে ১ মিনিট অপেক্ষা করুন।');
            });
        });
    }
}
