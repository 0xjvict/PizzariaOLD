<?php

namespace App\Providers;

use Domain\Order\Repositories\OrderRepository;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Order\Persistence\IlluminateOrderRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrderRepository::class,
            IlluminateOrderRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
