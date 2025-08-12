<?php

namespace App\Providers;

use App\Domain\IRepository\ICategoryRepository;
use App\Domain\IRepository\IProductRepository;
use App\Domain\IRepository\IStatusRepository;
use App\Domain\IRepository\ISupplierRepository;
use App\Domain\IRepository\IUserRepository;
use App\Infra\Repositories\Category\CategoryRepository;
use App\Infra\Repositories\Product\ProductRepository;
use App\Infra\Repositories\Status\StatusRepository;
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
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IStatusRepository::class, StatusRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}