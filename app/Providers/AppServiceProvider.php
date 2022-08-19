<?php

namespace App\Providers;

use App\Services\Interfaces\{
    AdditionalCostInterface,
    FloorInterface,
    PermissionInterface,
    SiteConfigurationInterface,
    UnitInterface,
    UnitTypeInterface,
    UserBatchInterface,
    RoleTypesInterface
};
use App\Services\{
    AdditionalCostService,
    FloorService,
    PermissionService,
    SiteConfiurationService,
    UnitService,
    UnitTypeService,
    UserBatchService,
    RoleTypesService
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
        $this->app->bind(UnitInterface::class, UnitService::class);
        $this->app->bind(SiteConfigurationInterface::class, SiteConfiurationService::class);
        $this->app->bind(UserBatchInterface::class, UserBatchService::class);
        $this->app->bind(RoleTypesInterface::class, RoleTypesService::class);

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
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
