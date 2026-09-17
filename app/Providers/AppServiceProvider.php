<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\ProductCategory;
use App\Repositories\BlogRepository;

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
        // Force HTTPS scheme when behind proxy (ngrok, load balancer, etc)
        // This fixes Mixed Content errors when accessing via HTTPS
        if (
            config('app.env') === 'production' ||
            request()->header('X-Forwarded-Proto') === 'https'
        ) {
            URL::forceScheme('https');
        }

        View::composer('layouts.public', function ($view) {
            // Sewaan categories for dropdown menu
            $view->with('sewaanCategories', ProductCategory::where('status', 'Sewaan')
                ->orderBy('name')
                ->get(['id', 'name', 'slug']));

            // Theme selection from blog settings
            $blogRepo = app(BlogRepository::class);
            $blog = $blogRepo->getSingleton();
            $theme = $blog->content['theme'] ?? 'default';
            $view->with('theme', $theme);
        });
    }
}
