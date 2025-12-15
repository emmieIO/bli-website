<?php

namespace App\Providers;

use App\Contracts\ProgramRepositoryInterface;
use App\Repositories\ProgramRepository;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(
            \App\Contracts\Repositories\MentorshipRepositoryInterface::class,
            \App\Repositories\MentorshipRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.sidebar', function ($view) {
            $sideLinks = config('sidebar.links');
            $view->with('sideLinks', $sideLinks);
        });
        Schema::defaultStringLength(191);
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Inertia::share([
            'sideLinks' => fn () => config('sidebar.links'),
        ]);
    }
}
