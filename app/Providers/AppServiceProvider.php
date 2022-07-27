<?php

namespace App\Providers;

use App\Services\Interfaces\{
    AdditionalCostInterface,
    FloorInterface,
    PermissionInterface,
    UnitTypeInterface
};
use App\Services\{
    AdditionalCostService,
    FloorService,
    PermissionService,
    UnitTypeService
};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UnitTypeInterface::class, UnitTypeService::class);
        $this->app->bind(PermissionInterface::class, PermissionService::class);
        $this->app->bind(AdditionalCostInterface::class, AdditionalCostService::class);
        $this->app->bind(FloorInterface::class, FloorService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
