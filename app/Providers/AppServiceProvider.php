<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\GigRepositoryInterface;
use App\Repositories\GigRepository;
use App\Repositories\CompanyRepositoryInterface;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GigRepositoryInterface::class, GigRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
