<?php

namespace App\Providers;

use App\Domain\IRepository\ISupplierRepository;
use App\Domain\IRepository\IUserRepository;
use App\Infra\Repositories\Supplier\SupplierRepository;
use App\Infra\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ISupplierRepository::class, SupplierRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}