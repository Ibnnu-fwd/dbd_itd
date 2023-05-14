<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Interface\ProvinceInterface::class, \App\Repositories\ProvinceRepository::class);
        $this->app->bind(\App\Repositories\Interface\RegencyInterface::class, \App\Repositories\RegencyRepository::class);
        $this->app->bind(\App\Repositories\Interface\DistrictInterface::class, \App\Repositories\DistrictRepository::class);
        $this->app->bind(\App\Repositories\Interface\TpaTypeInterface::class, \App\Repositories\TpaTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\FloorTypeInterface::class, \App\Repositories\FloorTypeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
