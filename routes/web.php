<?php

use App\Http\Controllers\{
    AccountsRecoveryController,
    AdditionalCostController,
    ArtisanCommandController,
    DashboardController,
    RoleController,
    PermissionController,
    TypeController,
    SiteController,
    CountryController,
    CustomFieldController,
    FileManagementController,
    FloorController,
    JobBatchController,
    LeadSourceController,
    SalesPlanController,
    testController,
    UnitController,
    PrintSalesPlanController,
    StakeholderController,
    NotificationController,
    ReceiptController,
    UserController,
    RebateIncentiveController,
    DealerIncentiveController,
    TeamController,
    FileRefundController,
    UnitShiftingController,
    FileAdjustmentController,
    FileTitleTransferController,
    FileReleaseController,
    FileCancellationController,
    FileBuyBackController,
};
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Activitylog\Models\Activity;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . DIRECTORY_SEPARATOR . 'auth.php';

Route::group([
    // 'prefix' => LaravelLocalization::setLocale(),
    // 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    Route::get('/', function () {
        return redirect()->route('login.view');
    });


    // Route::group(['middleware' => ['auth', ]], function () {
    Route::group(['middleware' => ['auth', 'permission']], function () {

        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::get('cachew/flush', [DashboardController::class, 'cacheFlush'])->name('site.cache.flush');

        //Role Routes
        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');

            Route::get('create', [RoleController::class, 'create'])->name('create');
            Route::post('store', [RoleController::class, 'store'])->name('store');

            Route::get('delete-selected', [RoleController::class, 'destroy'])->name('destroy-selected');
            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [RoleController::class, 'edit'])->name('edit');
                Route::put('update', [RoleController::class, 'update'])->name('update');
            });
        });

        //Permissions Routes
        Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');

            Route::get('create', [PermissionController::class, 'create'])->name('create');
            Route::post('store', [PermissionController::class, 'store'])->name('store');

            Route::get('delete-selected', [PermissionController::class, 'destroySelected'])->name('destroy-selected');
            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [PermissionController::class, 'edit'])->name('edit');
                Route::put('update', [PermissionController::class, 'update'])->name('update');

                Route::get('delete', [PermissionController::class, 'destroy'])->name('destroy');
            });

            Route::post('assign-permission', [PermissionController::class, 'assignPermissionToRole'])->name('assign-permission');
            Route::post('revoke-permission', [PermissionController::class, 'revokePermissionToRole'])->name('revoke-permission');
        });

        //Sites Routes
        Route::group(['prefix' => 'sites', 'as' => 'sites.'], function () {
            Route::get('/', [SiteController::class, 'index'])->name('index');

            Route::get('create', [SiteController::class, 'create'])->name('create');
            Route::post('store', [SiteController::class, 'store'])->name('store');

            Route::get('delete-selected', [SiteController::class, 'destroySelected'])->name('destroy-selected');

            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [SiteController::class, 'edit'])->name('edit');
                Route::put('update', [SiteController::class, 'update'])->name('update');
                Route::get('delete', [SiteController::class, 'destroy'])->name('destroy');

                Route::group(['prefix' => 'configurations', 'as' => 'configurations.'], function () {
                    Route::get('/', [SiteController::class, 'configView'])->name('configView');
                    Route::post('store', [SiteController::class, 'configStore'])->name('configStore');
                });
            });

            Route::group(['prefix' => '/{site_id}'], function () {

                Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {

                    //Custom Fields Routes
                    Route::group(['prefix' => 'custom-fields', 'as' => 'custom-fields.'], function () {
                        Route::get('/', [CustomFieldController::class, 'index'])->name('index');

                        Route::get('create', [CustomFieldController::class, 'create'])->name('create');
                        Route::post('store', [CustomFieldController::class, 'store'])->name('store');

                        Route::get('delete', [CustomFieldController::class, 'destroy'])->name('destroy');
                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [CustomFieldController::class, 'edit'])->name('edit');
                            Route::put('update', [CustomFieldController::class, 'update'])->name('update');
                        });
                    });
                });

                //Additional Costs Routes
                Route::group(['prefix' => 'additional-costs', 'as' => 'additional-costs.'], function () {
                    Route::get('/', [AdditionalCostController::class, 'index'])->name('index');

                    Route::get('create', [AdditionalCostController::class, 'create'])->name('create');
                    Route::post('store', [AdditionalCostController::class, 'store'])->name('store');

                    Route::get('delete-selected', [AdditionalCostController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [AdditionalCostController::class, 'edit'])->name('edit');
                        Route::put('update', [AdditionalCostController::class, 'update'])->name('update');

                        Route::get('delete', [AdditionalCostController::class, 'destroy'])->name('destroy');
                    });
                });

                //Floors Routes
                Route::group(['prefix' => 'floors', 'as' => 'floors.'], function () {

                    Route::get('/', [FloorController::class, 'index'])->name('index');

                    Route::get('create', [FloorController::class, 'create'])->name('create');
                    Route::post('store', [FloorController::class, 'store'])->name('store');

                    Route::get('delete-selected', [FloorController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [FloorController::class, 'edit'])->name('edit');
                        Route::put('update', [FloorController::class, 'update'])->name('update');
                    });

                    Route::get('get-pending-floors', [FloorController::class, 'getPendingFloors'])->name('pending.get');
                    Route::get('preview', [FloorController::class, 'preview'])->name('preview');
                    Route::get('save-changes', [FloorController::class, 'saveChanges'])->name('changes.save');
                    Route::get('copy', [FloorController::class, 'copyView'])->name('copyView');
                    Route::post('copy/store', [FloorController::class, 'copyStore'])->name('copyStore');

                    Route::view('importFloor', 'app.sites.floors.importFloors', ['preview' => false, 'final_preview' => false])->name('importFloors');
                    Route::post('importFloor', [FloorController::class, 'ImportPreview'])->name('importFloorsPreview');
                    Route::get('storePreview', [FloorController::class, 'storePreview'])->name('storePreview');
                    Route::post('saveImport', [FloorController::class, 'saveImport'])->name('saveImport');

                    // //Units Routes
                    Route::group(['prefix' => '/{floor_id}'], function () {

                        Route::group(['prefix' => 'units', 'as' => 'units.'], function () {
                            Route::get('/', [UnitController::class, 'index'])->name('index');

                            Route::get('create', [UnitController::class, 'create'])->name('create');
                            Route::post('store', [UnitController::class, 'store'])->name('store');

                            Route::group(['prefix' => 'fab', 'as' => 'fab.'], function () {
                                Route::get('create', [UnitController::class, 'createfabUnit'])->name('create');
                                Route::post('store', [UnitController::class, 'storefabUnit'])->name('store');
                            });

                            Route::get('preview', [UnitController::class, 'preview'])->name('preview');
                            Route::get('save-changes', [UnitController::class, 'saveChanges'])->name('changes.save');

                            Route::get('delete-selected', [UnitController::class, 'destroySelected'])->name('destroy-selected');

                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [UnitController::class, 'edit'])->name('edit');
                                Route::put('update', [UnitController::class, 'update'])->name('update');
                            });

                            Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                                Route::post('get-unit-data', [UnitController::class, 'getUnitData'])->name('get-unit-data');
                            });


                            Route::group(['prefix' => '/{unit_id}'], function () {

                                Route::group(['prefix' => 'sales-plans', 'as' => 'sales-plans.'], function () {

                                    Route::get('/', [SalesPlanController::class, 'index'])->name('index');

                                    Route::get('create', [SalesPlanController::class, 'create'])->name('create');
                                    Route::post('store', [SalesPlanController::class, 'store'])->name('store');
                                    Route::post('/approve-sales-plan', [SalesPlanController::class, 'approveSalesPlan'])->name('approve-sales-plan');


                                    Route::post('/disapprove-sales-plan', [SalesPlanController::class, 'disApproveSalesPlan'])->name('disapprove-sales-plan');
                                    Route::get('delete-selected', [SalesPlanController::class, 'destroySelected'])->name('destroy-selected');

                                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                                        Route::get('generate/installments', [SalesPlanController::class, 'ajaxGenerateInstallments'])->name('generate-installments');
                                    });

                                    Route::group(['prefix' => '/{id}'], function () {

                                        Route::get('edit', [SalesPlanController::class, 'edit'])->name('edit');
                                        Route::put('update', [SalesPlanController::class, 'update'])->name('update');
                                    });

                                    Route::group(['prefix' => '/{sales_plan_id}'], function () {

                                        Route::group(['prefix' => 'templates', 'as' => 'templates.'], function () {

                                            Route::group(['prefix' => '/{id}'], function () {
                                                Route::get('/print', [SalesPlanController::class, 'printPage'])->name('print');
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });

                //Types Routes
                Route::group(['prefix' => 'types', 'as' => 'types.'], function () {
                    Route::get('/', [TypeController::class, 'index'])->name('index');

                    Route::get('create', [TypeController::class, 'create'])->name('create');
                    Route::post('store', [TypeController::class, 'store'])->name('store');

                    Route::get('delete-selected', [TypeController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [TypeController::class, 'edit'])->name('edit');
                        Route::put('update', [TypeController::class, 'update'])->name('update');

                        Route::get('delete', [TypeController::class, 'destroy'])->name('destroy');
                    });
                });

                //Stakeholders Routes
                Route::group(['prefix' => 'stakeholders', 'as' => 'stakeholders.'], function () {
                    Route::get('/', [StakeholderController::class, 'index'])->name('index');

                    Route::get('create', [StakeholderController::class, 'create'])->name('create');
                    Route::post('store', [StakeholderController::class, 'store'])->name('store');

                    Route::get('delete-selected', [StakeholderController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [StakeholderController::class, 'edit'])->name('edit');
                        Route::put('update', [StakeholderController::class, 'update'])->name('update');
                        Route::get('delete', [StakeholderController::class, 'destroy'])->name('destroy');
                    });

                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.sites.stakeholders.importFloors', ['preview' => false, 'final_preview' => false])->name('importStakeholders');
                        Route::post('preview', [StakeholderController::class, 'ImportPreview'])->name('importStakeholdersPreview');
                        Route::get('storePreview', [StakeholderController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [StakeholderController::class, 'saveImport'])->name('saveImport');
                    });

                    Route::group(['prefix' => '/{id}/ajax', 'as' => 'ajax-'], function () {
                        Route::get('/', [StakeholderController::class, 'ajaxGetById'])->name('get-by-id');
                    });
                });

                //Users Routes
                Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
                    Route::get('/', [UserController::class, 'index'])->name('index');

                    Route::get('create', [UserController::class, 'create'])->name('create');
                    Route::post('store', [UserController::class, 'store'])->name('store');

                    Route::get('delete-selected', [UserController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [UserController::class, 'edit'])->name('edit');
                        Route::put('update', [UserController::class, 'update'])->name('update');
                        Route::get('delete', [UserController::class, 'destroy'])->name('destroy');
                    });

                    Route::group(['prefix' => '/{id}/ajax', 'as' => 'ajax-'], function () {
                        Route::get('/', [UserController::class, 'ajaxGetById'])->name('get-by-id');
                    });
                });

                //Teams Routes
                Route::group(['prefix' => 'teams', 'as' => 'teams.'], function () {
                    Route::get('/', [TeamController::class, 'index'])->name('index');

                    Route::get('create', [TeamController::class, 'create'])->name('create');
                    Route::post('store', [TeamController::class, 'store'])->name('store');

                    Route::get('delete-selected', [TeamController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [TeamController::class, 'edit'])->name('edit');
                        Route::put('update', [TeamController::class, 'update'])->name('update');
                        Route::get('delete', [TeamController::class, 'destroy'])->name('destroy');
                    });

                    Route::group(['prefix' => '/{id}/ajax', 'as' => 'ajax-'], function () {
                        Route::get('/', [TeamController::class, 'ajaxGetById'])->name('get-by-id');
                    });
                });

                //LeadSources Routes
                Route::group(['prefix' => 'lead-sources', 'as' => 'lead-sources.'], function () {
                    Route::get('/', [LeadSourceController::class, 'index'])->name('index');

                    Route::get('create', [LeadSourceController::class, 'create'])->name('create');
                    Route::post('store', [LeadSourceController::class, 'store'])->name('store');

                    Route::get('delete-selected', [LeadSourceController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [LeadSourceController::class, 'edit'])->name('edit');
                        Route::put('update', [LeadSourceController::class, 'update'])->name('update');
                    });
                });

                // Receipts
                Route::group(['prefix' => 'receipts', 'as' => 'receipts.'], function () {
                    Route::get('/', [ReceiptController::class, 'index'])->name('index');

                    Route::get('create', [ReceiptController::class, 'create'])->name('create');
                    Route::post('store', [ReceiptController::class, 'store'])->name('store');

                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        Route::post('get-unit-type-and-unit-floor', [ReceiptController::class, 'getUnitTypeAndFloorAjax'])->name('get-unit-type-and-unit-floor');
                        Route::post('get-unpaid-installments', [ReceiptController::class, 'getUnpaidInstallments'])->name('get-unpaid-installments');
                    });

                    Route::group(['prefix' => '/{receipts_id}'], function () {

                        Route::group(['prefix' => 'templates', 'as' => 'templates.'], function () {

                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('/print', [ReceiptController::class, 'printReceipt'])->name('print');
                            });
                        });
                    });

                    Route::get('destroy-draft', [ReceiptController::class, 'destroyDraft'])->name('destroy-draft');
                    Route::get('delete-selected', [ReceiptController::class, 'destroySelected'])->name('destroy-selected');
                    Route::get('make-active-selected', [ReceiptController::class, 'makeActiveSelected'])->name('make-active-selected');

                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('show', [ReceiptController::class, 'show'])->name('show');
                        // Route::get('edit', [ReceiptController::class, 'edit'])->name('edit');
                        // Route::put('update', [ReceiptController::class, 'update'])->name('update');
                        // Route::get('delete', [ReceiptController::class, 'destroy'])->name('destroy');
                    });
                });

                // File Management
                Route::group(['prefix' => 'file-managements', 'as' => 'file-managements.'], function () {

                    Route::get('/customers', [FileManagementController::class, 'customers'])->name('customers');
                    Route::get('/view-files', [FileManagementController::class, 'viewFiles'])->name('view-files');
                    // rebate incentive form
                    Route::group(['prefix' => 'rebate-incentive', 'as' => 'rebate-incentive.'], function () {

                        Route::get('/', [RebateIncentiveController::class, 'index'])->name('index');

                        Route::get('create', [RebateIncentiveController::class, 'create'])->name('create');
                        Route::post('store', [RebateIncentiveController::class, 'store'])->name('store');
                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [RebateIncentiveController::class, 'edit'])->name('edit');
                            Route::put('update', [RebateIncentiveController::class, 'update'])->name('update');
                        });
                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::post('get-data', [RebateIncentiveController::class, 'getData'])->name('get-data');
                        });
                    });

                    // dealer incentive form
                    Route::group(['prefix' => 'dealer-incentive', 'as' => 'dealer-incentive.'], function () {

                        Route::get('/', [DealerIncentiveController::class, 'index'])->name('index');

                        Route::get('create', [DealerIncentiveController::class, 'create'])->name('create');
                        Route::post('store', [DealerIncentiveController::class, 'store'])->name('store');

                        Route::get('approve/{dealer_incentive_id}', [DealerIncentiveController::class, 'approve'])->name('approve');

                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::post('get-data', [DealerIncentiveController::class, 'getData'])->name('get-data');
                        });
                    });

                    // file refund
                    Route::group(['prefix' => 'file-refund', 'as' => 'file-refund.'], function () {

                        Route::get('/', [FileRefundController::class, 'index'])->name('index');

                        Route::get('approve/{unit_id}/{customer_id}/{file_refund_id}', [FileRefundController::class, 'ApproveFileRefund'])->name('approve');
                        Route::get('create/{unit_id}/{customer_id}/{file_id}', [FileRefundController::class, 'create'])->name('create');
                        Route::post('store', [FileRefundController::class, 'store'])->name('store');
                        Route::get('preview/{unit_id}/{customer_id}/{file_refund_id}', [FileRefundController::class, 'show'])->name('preview');

                        Route::get('/print/{file_refund_id}/{template_id}', [FileRefundController::class, 'printPage'])->name('print');
                    });

                    // file buy back
                    Route::group(['prefix' => 'file-buy-back', 'as' => 'file-buy-back.'], function () {

                        Route::get('/', [FileBuyBackController::class, 'index'])->name('index');
                        Route::get('approve/{unit_id}/{customer_id}/{file_buy_back_id}', [FileBuyBackController::class, 'ApproveFileBuyBack'])->name('approve');
                        Route::get('create/{unit_id}/{customer_id}/{file_id}', [FileBuyBackController::class, 'create'])->name('create');
                        Route::post('store', [FileBuyBackController::class, 'store'])->name('store');
                        Route::get('preview/{unit_id}/{customer_id}/{file_buy_back_id}', [FileBuyBackController::class, 'show'])->name('preview');

                        Route::get('/print/{file_buy_back_id}/{template_id}', [FileBuyBackController::class, 'printPage'])->name('print');
                    });

                    // file Cancellation
                    Route::group(['prefix' => 'file-cancellation', 'as' => 'file-cancellation.'], function () {

                        Route::get('/', [FileCancellationController::class, 'index'])->name('index');
                        Route::get('approve/{unit_id}/{customer_id}/{file_cancellation_id}', [FileCancellationController::class, 'ApproveFileCancellation'])->name('approve');

                        Route::get('create/{unit_id}/{customer_id}/{file_id}', [FileCancellationController::class, 'create'])->name('create');
                        Route::post('store', [FileCancellationController::class, 'store'])->name('store');
                        Route::get('preview/{unit_id}/{customer_id}/{file_cancellation_id}', [FileCancellationController::class, 'show'])->name('preview');

                        Route::get('/print/{file_cancellation_id}/{template_id}', [FileCancellationController::class, 'printPage'])->name('print');
                    });

                    // file resalse
                    Route::group(['prefix' => 'file-resale', 'as' => 'file-resale.'], function () {

                        Route::get('/', [FileReleaseController::class, 'index'])->name('index');
                        Route::get('approve/{unit_id}/{customer_id}/{file_resale_id}', [FileReleaseController::class, 'ApproveFileResale'])->name('approve');

                        Route::get('create/{unit_id}/{customer_id}/{file_id}', [FileReleaseController::class, 'create'])->name('create');
                        Route::post('store', [FileReleaseController::class, 'store'])->name('store');
                        Route::get('preview/{unit_id}/{customer_id}/{file_resale_id}', [FileReleaseController::class, 'show'])->name('preview');

                        Route::get('/print/{file_resale_id}/{template_id}', [FileReleaseController::class, 'printPage'])->name('print');
                    });

                    // file title transfer
                    Route::group(['prefix' => 'file-title-transfer', 'as' => 'file-title-transfer.'], function () {

                        Route::get('/', [FileTitleTransferController::class, 'index'])->name('index');
                        Route::get('approve/{unit_id}/{customer_id}/{file_title_transfer_id}', [FileTitleTransferController::class, 'ApproveFileTitleTransfer'])->name('approve');

                        Route::get('create/{unit_id}/{customer_id}/{file_id}', [FileTitleTransferController::class, 'create'])->name('create');
                        Route::post('store', [FileTitleTransferController::class, 'store'])->name('store');
                        Route::get('preview/{unit_id}/{customer_id}/{file_title_transfer_id}', [FileTitleTransferController::class, 'show'])->name('preview');

                        Route::get('/print/{file_title_transfer_id}/{template_id}', [FileTitleTransferController::class, 'printPage'])->name('print');
                    });

                    // file adjustment
                    Route::group(['prefix' => 'file-adjustment', 'as' => 'file-adjustment.'], function () {

                        Route::get('/', [FileAdjustmentController::class, 'index'])->name('index');

                        Route::get('create/{unit_id}/{customer_id}', [FileAdjustmentController::class, 'create'])->name('create');
                        Route::post('store', [FileAdjustmentController::class, 'store'])->name('store');
                    });

                    // Unit Shifting
                    Route::group(['prefix' => 'unit-shifting', 'as' => 'unit-shifting.'], function () {

                        Route::get('/', [UnitShiftingController::class, 'index'])->name('index');

                        Route::get('create/{unit_id}/{customer_id}', [UnitShiftingController::class, 'create'])->name('create');
                        Route::post('store', [UnitShiftingController::class, 'store'])->name('store');
                    });

                    Route::group(['prefix' => 'customers/{customer_id}', 'as' => 'customers.'], function () {
                        Route::get('/units', [FileManagementController::class, 'units'])->name('units');

                        Route::group(['prefix' => 'units/{unit_id}', 'as' => 'units.'], function () {

                            //Files Routes
                            Route::group(['prefix' => 'files', 'as' => 'files.'], function () {
                                Route::get('/', [FileManagementController::class, 'index'])->name('index');

                                Route::get('/show/{file_id}', [FileManagementController::class, 'show'])->name('show');
                                Route::get('/print/{file_id}', [FileManagementController::class, 'print'])->name('print');

                                Route::get('create', [FileManagementController::class, 'create'])->name('create');
                                Route::post('store', [FileManagementController::class, 'store'])->name('store');

                                Route::get('delete-selected', [FileManagementController::class, 'destroySelected'])->name('destroy-selected');

                                Route::group(['prefix' => '/{id}'], function () {
                                    Route::get('edit', [FileManagementController::class, 'edit'])->name('edit');
                                    Route::put('update', [FileManagementController::class, 'update'])->name('update');
                                });
                            });
                        });
                    });
                });

                // Accounts Routes
                Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {

                    // Route::get('/', [AdditionalCostController::class, 'index'])->name('index');

                    // Accounts Recovery Routes
                    Route::group(['prefix' => 'recovery', 'as' => 'recovery.'], function () {
                        Route::get('/dashboard', [AccountsRecoveryController::class, 'dashboard'])->name('dashboard');
                        Route::get('/calender', [AccountsRecoveryController::class, 'calender'])->name('calender');
                        Route::get('/sales-plans', [AccountsRecoveryController::class, 'salesPlan'])->name('salesPlan');

                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::post('get-filtered-calender-events', [AccountsRecoveryController::class, 'getFilteredUnitData'])->name('get-filtered-calender-events');
                        });
                    });
                });
            });
        });

        Route::get('ajax-get-unit-input', [UnitController::class, 'getUnitInput'])->name('ajax-unit.get.input');
        Route::get('ajax-draw-facing-field', [UnitController::class, 'drawFacingField'])->name('ajax-facing.field.draw');
        Route::get('ajax-update-unit-name', [UnitController::class, 'updateUnitName'])->name('ajax-unit.name.update');

        Route::get('ajax-import-floor.get.input', [FloorController::class, 'getUnitInput'])->name('ajax-import-floor.get.input');
        Route::get('ajax-import-floor.error.inputs', [FloorController::class, 'UpdateErrorInput'])->name('ajax-import-floor.error.inputs');

        //Countries Routes
        Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {
            Route::get('cities', [CountryController::class, 'getCities'])->name('cities');
        });

        Route::group(['prefix' => 'batches', 'as' => 'batches.'], function () {
            Route::get('/{batch_id}', [JobBatchController::class, 'getJobBatchByID'])->name('byid');
        });

        Route::group(['prefix' => 'commands', 'as' => 'commands.'], function () {
            Route::get('/{command}', [ArtisanCommandController::class, 'commands'])->name('command');
        });
    });
});

Route::group(['prefix' => 'tests'], function () {
    Route::get('test', [testController::class, 'test'])->name('test');
    Route::get('jobs', [testController::class, 'jobs'])->name('jobs');
    Route::get('/batch/{batchId}', [testController::class, 'getBatchByID'])->name('batch');
    Route::get('/session/{batchId}', [testController::class, 'setBatchIDInSession'])->name('sbatch');
    Route::get('/session/{batchId}/remove', [testController::class, 'unsetBatchIDInSession'])->name('ssbatch');
    Route::get('activitylogs', [testController::class, 'activityLog']);
});

Route::get('/read-all-notifications', [NotificationController::class, 'readAllNotifications']);
Route::post('/read-single-notification', [NotificationController::class, 'readSingleNotification']);

Route::get('/logs', function () {
    return Activity::latest()->get();
});

// Route::get('/fire', function () {
//     $data = [
//         'title' => 'Job Done!',
//         'message' => 'Unit Construction Completed',
//         'description' => 'Unit Construction Completed',
//         'url' => 'asdadasd',
//     ];
//     Notification::sendNow(auth()->user(), new DefaultNotification($data));

//     return 'fire';
// });

Route::get('/createaccount', function () {
    makeSalesPlanTransaction(1);
});



Route::post('storePreviewtest', function () {
    dd(request()->all());
})->name('storePreviewtest');
