<?php

namespace App\Providers;

use App\Http\Repository\IRepository;
use App\Http\Repository\ProjectRepository;
use App\Http\Repository\UserRepository;
use App\Services\PaginationServices;
use App\Services\ProjectServices;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(IRepository::class, UserRepository::class);
        $this->app->bind(IRepository::class, ProjectRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

    }
}
