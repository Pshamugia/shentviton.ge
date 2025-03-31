<?php

namespace App\Providers;

use App\Repositories\PaymentRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\Payments\PaymentGatewayFactory;
use App\Services\Payments\PaymentGatewayInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayInterface::class, fn() => PaymentGatewayFactory::make());

        $this->app->bind(PaymentRepositoryInterface::class, function ($app) {
            return new PaymentRepository($app->make(PaymentGatewayInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
