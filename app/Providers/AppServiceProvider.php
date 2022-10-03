<?php

namespace App\Providers;

use App\Services\Interfaces\{
    FloorInterface,
    SiteConfigurationInterface,
    UnitInterface,
    UnitTypeInterface,
    UserBatchInterface,
};
use App\Services\{
    FloorService,
    SiteConfiurationService,
    UnitService,
    UnitTypeService,
    UserBatchService,
};
use App\Services\AdditionalCosts\{AdditionalCostInterface, AdditionalCostService};
use App\Services\FileManagements\{FileManagementInterface, FileManagementService};
use App\Services\FileManagements\FileActions\Refund\RefundInterface;
use App\Services\FileManagements\FileActions\Refund\RefundService;
use App\Services\SalesPlan\{SalesPlanService, Interface\SalesPlanInterface};
use App\Services\Stakeholder\{StakeholderService, Interface\StakeholderInterface};
use App\Services\User\{UserService, Interface\UserInterface};
use App\Services\Team\{TeamService, Interface\TeamInterface};
use App\Services\LeadSource\{LeadSourceService, LeadSourceInterface};
use App\Services\Permissions\{PermissionInterface, PermissionService};
use App\Services\Receipts\{ReceiptService, Interface\ReceiptInterface};
use App\Services\RebateIncentive\{RebateIncentiveInterface, RebateIncentiveService};
use App\Services\Roles\{RoleInterface, RoleService};
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
        $this->app->bind(RoleInterface::class, RoleService::class);
        $this->app->bind(SalesPlanInterface::class, SalesPlanService::class);
        $this->app->bind(StakeholderInterface::class, StakeholderService::class);
        $this->app->bind(UserInterface::class, UserService::class);
        $this->app->bind(TeamInterface::class, TeamService::class);
        $this->app->bind(LeadSourceInterface::class, LeadSourceService::class);
        $this->app->bind(ReceiptInterface::class, ReceiptService::class);
        $this->app->bind(FileManagementInterface::class, FileManagementService::class);
        $this->app->bind(RebateIncentiveInterface::class, RebateIncentiveService::class);
        $this->app->bind(RefundInterface::class, RefundService::class);

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
