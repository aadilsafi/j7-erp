<?php

namespace App\Providers;

use App\Services\Interfaces\{FloorInterface, SiteConfigurationInterface, UnitInterface, UnitTypeInterface, UserBatchInterface};
use App\Services\{FloorService, SiteConfiurationService, UnitService, UnitTypeService, UserBatchService};
use App\Services\AccountRecevories\AccountRecevoryInterface;
use App\Services\AccountRecevories\AccountRecevoryService;
use App\Services\AdditionalCosts\{AdditionalCostInterface, AdditionalCostService};
use App\Services\CustomFields\{CustomFieldInterface, CustomFieldService};
use App\Services\CustomFieldValues\{CustomFieldValueInterface};
use App\Services\DealerIncentive\{DealerInterface, DealerService};
use App\Services\FileManagements\{FileManagementInterface, FileManagementService};
use App\Services\FileManagements\FileActions\BuyBack\{BuyBackInterface, BuyBackService};
use App\Services\FileManagements\FileActions\Cancellation\{CancellationInterface, CancellationService};
use App\Services\FileManagements\FileActions\Refund\{RefundInterface, RefundService};
use App\Services\FileManagements\FileActions\Resale\{ResaleInterface, ResaleService};
use App\Services\FileManagements\FileActions\TitleTransfer\{TitleTransferInterface, TitleTransferService};
use App\Services\FinancialTransactions\{FinancialTransactionInterface, FinancialTransactionService};
use App\Services\SalesPlan\{SalesPlanService, Interface\SalesPlanInterface};
use App\Services\Stakeholder\{StakeholderService, Interface\StakeholderInterface};
use App\Services\User\{UserService, Interface\UserInterface};
use App\Services\Team\{TeamService, Interface\TeamInterface};
use App\Services\LeadSource\{LeadSourceService, LeadSourceInterface};
use App\Services\Permissions\{PermissionInterface, PermissionService};
use App\Services\Receipts\{ReceiptService, Interface\ReceiptInterface};
use App\Services\TransferFileReceipts\{TransferFileReceiptService, TransferFileReceiptInterface};
use App\Services\RebateIncentive\{RebateIncentiveInterface, RebateIncentiveService};
use App\Services\Roles\{RoleInterface, RoleService};
use App\Services\AccountCreations\FirstLevel\FirstLevelAccountinterface as FirstLevelAccountInterface;
use App\Services\AccountCreations\FirstLevel\FirstLevelAccountservice as FirstLevelAccountService;
use App\Services\AccountCreations\SecondLevel\SecondLevelAccountinterface as SecondLevelAccountInterface;
use App\Services\AccountCreations\SecondLevel\SecondLevelAccountservice as SecondLevelAccountService;
use App\Services\AccountCreations\ThirdLevel\ThirdLevelAccountinterface as ThirdLevelAccountInterface;
use App\Services\AccountCreations\ThirdLevel\ThirdLevelAccountservice as ThirdLevelAccountService;
use App\Services\AccountCreations\FourthLevel\FourthLevelACcountinterface as FourthLevelAccountInterface;
use App\Services\AccountCreations\FourthLevel\FourthLevelAccountservice as FourthLevelAccountService;
use App\Services\AccountCreations\FifthLevel\FifthLevelAccountinterface as FifthLevelAccountInterface;
use App\Services\AccountCreations\FifthLevel\FifthLevelAccountservice as FifthLevelAccountService;
use App\Services\Company\{CompanyService, Interface\CompanyInterface};
use App\Services\JournalVouchers\JournalVouchersInterface;
use App\Services\JournalVouchers\JournalVouchersService;
use App\Services\PaymentVoucher\paymentService;
use App\Services\PaymentVoucher\paymentInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;

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
        $this->app->bind(BuyBackInterface::class, BuyBackService::class);
        $this->app->bind(CancellationInterface::class, CancellationService::class);
        $this->app->bind(ResaleInterface::class, ResaleService::class);
        $this->app->bind(TitleTransferInterface::class, TitleTransferService::class);
        $this->app->bind(DealerInterface::class, DealerService::class);
        $this->app->bind(CustomFieldInterface::class, CustomFieldService::class);
        $this->app->bind(CustomFieldValueInterface::class, CustomFieldService::class);
        $this->app->bind(AccountRecevoryInterface::class, AccountRecevoryService::class);
        $this->app->bind(FinancialTransactionInterface::class, FinancialTransactionService::class);
        $this->app->bind(FirstLevelAccountInterface::class, FirstLevelAccountService::class);
        $this->app->bind(SecondLevelAccountInterface::class, SecondLevelAccountService::class);
        $this->app->bind(ThirdLevelAccountInterface::class, ThirdLevelAccountService::class);
        $this->app->bind(FourthLevelAccountInterface::class, FourthLevelAccountService::class);
        $this->app->bind(FifthLevelAccountInterface::class, FifthLevelAccountService::class);
        $this->app->bind(CompanyInterface::class, CompanyService::class);
        $this->app->bind(paymentInterface::class, paymentService::class);
        $this->app->bind(JournalVouchersInterface::class, JournalVouchersService::class);
        $this->app->bind(TransferFileReceiptInterface::class, TransferFileReceiptService::class);

        Telescope::ignoreMigrations();

        // if ($this->app->environment('local')) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
        // }
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
