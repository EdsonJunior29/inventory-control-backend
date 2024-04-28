<?php

namespace App\Providers;

use App\Domain\Repository\GetUserRepository;
use App\Infra\User\GetUser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GetUserRepository::class, GetUser::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
