<?php

namespace App\Providers;

use App\Contracts\Auth\UserServiceInterface;
use App\Services\Auth\UserService;
use App\Models\Course;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Models\Category;
use App\Policies\CoursePolicy;
use App\Policies\EventPolicy;
use App\Policies\SpeakerPolicy;
use App\Policies\SpeakerApplicationPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        Event::class => EventPolicy::class,
        Speaker::class => SpeakerPolicy::class,
        SpeakerApplication::class => SpeakerApplicationPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Register any additional gates here if needed
    }
}
