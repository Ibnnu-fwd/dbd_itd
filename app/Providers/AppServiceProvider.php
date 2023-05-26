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
        $this->app->bind(\App\Repositories\Interface\EnvironmentTypeInterface::class, \App\Repositories\EnvironmentTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\VillageInterface::class, \App\Repositories\VillageRepository::class);
        $this->app->bind(\App\Repositories\Interface\LocationTypeInterface::class, \App\Repositories\LocationTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\SettlementTypeInterface::class, \App\Repositories\SettlementTypeRepository::class);
        $this->app->bind(\App\Repositories\Interface\BuildingTypeInterface::class, \App\Repositories\BuildingTypeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
