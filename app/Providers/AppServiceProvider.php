<?php

namespace App\Providers;

use App\Contracts\ProgramRepositoryInterface;
use App\Contracts\Repositories\MentorshipRepositoryInterface;
use App\Contracts\Services\PaymentGatewayInterface;
use App\Repositories\MentorshipRepository;
use App\Repositories\ProgramRepository;
use App\Services\Payment\PaystackService;
use App\Support\PlatformModuleRegistry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(
            MentorshipRepositoryInterface::class,
            MentorshipRepository::class
        );
        $this->app->bind(PaymentGatewayInterface::class, PaystackService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.sidebar', function ($view) {
            $view->with('sideLinks', app(PlatformModuleRegistry::class)->sideLinks(Auth::user()));
        });

        Schema::defaultStringLength(191);
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
