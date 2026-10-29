<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\ProductPriceRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Eloquent\ProductPriceRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\CurrencyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository interface bindings.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductPriceRepositoryInterface::class, ProductPriceRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
