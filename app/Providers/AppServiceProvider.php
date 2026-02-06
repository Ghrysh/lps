<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL; // <-- tambahkan ini
use App\Models\Point;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force all URLs to use HTTPS di environment production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // View composer untuk total points
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $totalPoints = Point::where('user_id', Auth::id())->sum('nilai');
            } else {
                $totalPoints = 0;
            }

            $view->with('totalPoints', $totalPoints);
        });
    }
}
