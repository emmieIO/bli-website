<?php

namespace App\Providers;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Repositories\LessonProgressRepositoryInterface;
use App\Contracts\Services\CertificateServiceInterface;
use App\Contracts\Services\LearningProgressServiceInterface;
use App\Repositories\CourseRepository;
use App\Repositories\LessonProgressRepository;
use App\Services\CertificateService;
use App\Services\LearningProgressService;
use Illuminate\Support\ServiceProvider;

class LearningServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(LessonProgressRepositoryInterface::class, LessonProgressRepository::class);

        // Service bindings
        $this->app->bind(LearningProgressServiceInterface::class, LearningProgressService::class);
        $this->app->bind(CertificateServiceInterface::class, CertificateService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
