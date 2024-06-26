<?php

use App\Http\Controllers\{
    AccountsRecoveryController,
    AdditionalCostController,
    ArtisanCommandController,
    BankController,
    BinController,
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
    StakeholderController,
    BacklistedStakeholderController,
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
    ChartsOfAccountsController,
    CityController,
    CompanyController,
    ImageImportController,
    LedgerController,
    SalesPlanImportController,
    GeneralLedgerController,
    JournalEntryController,
    FirstLevelAccountController,
    SecondLevelAccountController,
    ThirdLevelAccountController,
    FourthLevelAccountController,
    FifthLevelAccountController,
    StakeholderKinsImportControler,
    StateController,
    LogController,
    PaymentVocuherController,
    JournalVoucherController,
    TransferReceiptController,
    JournalVoucherEntriesController,
    StakeholderContactsImportControler,
    StakeholderImportController,
    StakeholderInvestorController,
    InvsetorDealsReceiptController,
};
use App\Models\PaymentVocuher;
use App\Models\Type;
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
    // Route::group(['middleware' => ['crm_api']], function () {
    //     Route::group(['prefix' => 'sites', 'as' => 'sites.'], function () {
    //         Route::group(['prefix' => '/{site_id}'], function () {
    //             Route::group(['prefix' => 'sales_plan', 'as' => 'sales_plan.'], function () {
    //                 // Route::get('{user_id}/generate/{crm_lead}', [SalesPlanController::class, 'generateSalesPlan'])->name('generateSalesPlan');
    //             });
    //         });
    //     });
    // });

    // Route::group(['middleware' => ['auth', ]], function () {
    Route::group(['middleware' => ['auth', 'permission']], function () {

        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
            Route::post('get-filtered-data-dasboard', [DashboardController::class, 'dasboard_chart'])->name('get-filtered-data-dasboard');
            Route::post('get-dasboard-side-chart', [DashboardController::class, 'dasboardSideChart'])->name('get-dasboard-side-chart');
        });

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

                    //Accounts Routes
                    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {

                        // first level
                        Route::group(['prefix' => 'first-level', 'as' => 'first-level.'], function () {
                            Route::get('/', [FirstLevelAccountController::class, 'index'])->name('index');

                            Route::get('create', [FirstLevelAccountController::class, 'create'])->name('create');
                            Route::post('store', [FirstLevelAccountController::class, 'store'])->name('store');

                            Route::get('delete', [FirstLevelAccountController::class, 'destroy'])->name('destroy');
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [FirstLevelAccountController::class, 'edit'])->name('edit');
                                Route::put('update', [FirstLevelAccountController::class, 'update'])->name('update');
                            });
                        });

                        // second level
                        Route::group(['prefix' => 'second-level', 'as' => 'second-level.'], function () {
                            Route::get('/', [SecondLevelAccountController::class, 'index'])->name('index');

                            Route::get('create', [SecondLevelAccountController::class, 'create'])->name('create');
                            Route::post('store', [SecondLevelAccountController::class, 'store'])->name('store');

                            Route::get('delete', [SecondLevelAccountController::class, 'destroy'])->name('destroy');
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [SecondLevelAccountController::class, 'edit'])->name('edit');
                                Route::put('update', [SecondLevelAccountController::class, 'update'])->name('update');
                            });
                        });

                        // third level
                        Route::group(['prefix' => 'third-level', 'as' => 'third-level.'], function () {
                            Route::get('/', [ThirdLevelAccountController::class, 'index'])->name('index');

                            Route::get('create', [ThirdLevelAccountController::class, 'create'])->name('create');
                            Route::post('store', [ThirdLevelAccountController::class, 'store'])->name('store');

                            Route::get('delete', [ThirdLevelAccountController::class, 'destroy'])->name('destroy');
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [ThirdLevelAccountController::class, 'edit'])->name('edit');
                                Route::put('update', [ThirdLevelAccountController::class, 'update'])->name('update');
                            });
                        });

                        // fourth level
                        Route::group(['prefix' => 'fourth-level', 'as' => 'fourth-level.'], function () {
                            Route::get('/', [FourthLevelAccountController::class, 'index'])->name('index');

                            Route::get('create', [FourthLevelAccountController::class, 'create'])->name('create');
                            Route::post('store', [FourthLevelAccountController::class, 'store'])->name('store');

                            Route::get('delete', [FourthLevelAccountController::class, 'destroy'])->name('destroy');
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [FourthLevelAccountController::class, 'edit'])->name('edit');
                                Route::put('update', [FourthLevelAccountController::class, 'update'])->name('update');
                            });
                        });

                        // fifth level
                        Route::group(['prefix' => 'fifth-level', 'as' => 'fifth-level.'], function () {
                            Route::get('/', [FifthLevelAccountController::class, 'index'])->name('index');

                            Route::get('create', [FifthLevelAccountController::class, 'create'])->name('create');
                            Route::post('store', [FifthLevelAccountController::class, 'store'])->name('store');

                            Route::get('delete', [FifthLevelAccountController::class, 'destroy'])->name('destroy');
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [FifthLevelAccountController::class, 'edit'])->name('edit');
                                Route::put('update', [FifthLevelAccountController::class, 'update'])->name('update');
                            });
                        });
                    });

                    // Journal Voucher Routes
                    Route::group(['prefix' => 'journal-vouchers', 'as' => 'journal-vouchers.'], function () {

                        Route::get('/', [JournalVoucherController::class, 'index'])->name('index');

                        Route::get('create', [JournalVoucherController::class, 'create'])->name('create');
                        Route::post('store', [JournalVoucherController::class, 'store'])->name('store');

                        Route::get('delete', [JournalVoucherController::class, 'destroy'])->name('destroy');

                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [JournalVoucherController::class, 'edit'])->name('edit');
                            Route::put('update', [JournalVoucherController::class, 'update'])->name('update');
                            Route::get('show', [JournalVoucherController::class, 'show'])->name('show');

                            Route::group(['prefix' => 'journal-vouchers-entries', 'as' => 'journal-vouchers-entries.'], function () {

                                Route::get('check-voucher', [JournalVoucherController::class, 'checkVoucher'])->name('check-voucher');
                                Route::get('post-voucher', [JournalVoucherController::class, 'postVoucher'])->name('post-voucher');
                                Route::get('revert-voucher', [JournalVoucherController::class, 'revertVoucher'])->name('revert-voucher');
                                Route::get('dis-approve-voucher', [JournalVoucherController::class, 'disapproveVoucher'])->name('dis-approve-voucher');
                            });
                        });

                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        });

                        // Import
                        Route::group(['prefix' => 'import'], function () {
                            Route::view('/', 'app.sites.journal-vouchers.import.importjv')->name('importJournalVoucher');
                            Route::post('preview', [JournalVoucherController::class, 'ImportPreview'])->name('importJournalVoucherPreview');
                            Route::get('storePreview', [JournalVoucherController::class, 'storePreview'])->name('storePreview');
                            Route::post('saveImport', [JournalVoucherController::class, 'saveImport'])->name('saveImport');
                        });
                    });



                    // Import Images Routes
                    Route::group(['prefix' => 'import', 'as' => 'import.'], function () {
                        Route::group(['prefix' => 'images', 'as' => 'images.'], function () {

                            Route::get('/', [ImageImportController::class, 'index'])->name('index');

                            Route::get('create', [ImageImportController::class, 'create'])->name('create');
                            Route::post('store', [ImageImportController::class, 'store'])->name('store');
                            Route::get('cancel', [ImageImportController::class, 'cancel'])->name('cancel');
                        });
                    });

                    //Countries Route
                    Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {

                        Route::get('/', [CountryController::class, 'index'])->name('index');

                        Route::get('create', [CountryController::class, 'create'])->name('create');
                        Route::post('store', [CountryController::class, 'store'])->name('store');

                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [CountryController::class, 'edit'])->name('edit');
                            Route::put('update', [CountryController::class, 'update'])->name('update');
                            Route::get('delete', [CountryController::class, 'destroy'])->name('destroy');
                        });
                    });

                    //States Route
                    Route::group(['prefix' => 'states', 'as' => 'states.'], function () {

                        Route::get('/', [StateController::class, 'index'])->name('index');

                        Route::get('create', [StateController::class, 'create'])->name('create');
                        Route::post('store', [StateController::class, 'store'])->name('store');

                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [StateController::class, 'edit'])->name('edit');
                            Route::put('update', [StateController::class, 'update'])->name('update');
                            Route::get('delete', [StateController::class, 'destroy'])->name('destroy');
                        });
                    });

                    //Cities Route
                    Route::group(['prefix' => 'cities', 'as' => 'cities.'], function () {

                        Route::get('/', [CityController::class, 'index'])->name('index');

                        Route::get('create', [CityController::class, 'create'])->name('create');
                        Route::post('store', [CityController::class, 'store'])->name('store');

                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [CityController::class, 'edit'])->name('edit');
                            Route::put('update', [CityController::class, 'update'])->name('update');
                            Route::get('delete', [CityController::class, 'destroy'])->name('destroy');
                        });
                    });

                    // Bin Route
                    Route::group(['prefix' => 'bin', 'as' => 'bin.'], function () {

                        Route::get('type', [BinController::class, 'type'])->name('type');
                        Route::get('unit', [BinController::class, 'unit'])->name('unit');
                        Route::post('additionalcosts', [BinController::class, 'additionalcosts'])->name('additionalcosts');

                        Route::group(['prefix' => '/{id}'], function () {
                            Route::put('restore', [BinController::class, 'restore'])->name('restore');
                            Route::get('delete', [BinController::class, 'destroy'])->name('destroy');
                        });
                    });

                    //Company Routes
                    Route::group(['prefix' => 'companies', 'as' => 'companies.'], function () {

                        Route::get('/', [CompanyController::class, 'index'])->name('index');

                        Route::get('create', [CompanyController::class, 'create'])->name('create');
                        Route::post('store', [CompanyController::class, 'store'])->name('store');

                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [CompanyController::class, 'edit'])->name('edit');
                            Route::put('update', [CompanyController::class, 'update'])->name('update');
                            Route::get('delete', [CompanyController::class, 'destroy'])->name('destroy');
                        });
                        Route::get('delete-selected', [FloorController::class, 'destroySelected'])->name('destroy-selected');
                    });
                    // Logs Route
                    Route::group(['prefix' => 'activity-logs', 'as' => 'activity-logs.'], function () {
                        Route::get('/', [LogController::class, 'index'])->name('index');
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

                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.additional-costs.importAdcosts')->name('importAdcosts');
                        Route::post('preview', [AdditionalCostController::class, 'ImportPreview'])->name('importAdcostsPreview');
                        Route::get('storePreview', [AdditionalCostController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [AdditionalCostController::class, 'saveImport'])->name('saveImport');
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
                        Route::get('floor-plan', [FloorController::class, 'floorPlan'])->name('floor-plan');
                        Route::post('floor-plan/upload', [FloorController::class, 'floorPlanUpload'])->name('floor-plan.upload');
                        Route::put('update', [FloorController::class, 'update'])->name('update');
                    });

                    Route::get('get-pending-floors', [FloorController::class, 'getPendingFloors'])->name('pending.get');
                    Route::get('preview', [FloorController::class, 'preview'])->name('preview');
                    Route::get('save-changes', [FloorController::class, 'saveChanges'])->name('changes.save');
                    Route::get('copy', [FloorController::class, 'copyView'])->name('copyView');
                    Route::post('copy/store', [FloorController::class, 'copyStore'])->name('copyStore');

                    //Floor Import routes

                    Route::view('importFloor', 'app.sites.floors.importFloors', ['preview' => false, 'final_preview' => false])->name('importFloors');
                    Route::post('importFloor', [FloorController::class, 'ImportPreview'])->name('importFloorsPreview');
                    Route::get('storePreview', [FloorController::class, 'storePreview'])->name('storePreview');
                    Route::post('saveImport', [FloorController::class, 'saveImport'])->name('saveImport');

                    //Units Import routes
                    Route::group(['prefix' => 'Unit/import', 'as' => 'unitsImport.'], function () {
                        Route::view('/', 'app.sites.floors.units.importUnits')->name('importUnits');
                        Route::post('preview', [UnitController::class, 'ImportPreview'])->name('importUnitsPreview');
                        Route::get('storePreview', [UnitController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [UnitController::class, 'saveImport'])->name('saveImport');
                    });

                    //Sales Plan Import routes
                    Route::group(['prefix' => 'salesPlan/import', 'as' => 'SalesPlanImport.'], function () {
                        Route::view('/', 'app.sites.floors.units.sales-plan.import.importSalesPlan')->name('importSalesPlan');
                        Route::post('preview', [SalesPlanController::class, 'ImportPreview'])->name('importSalesPlanPreview');
                        Route::get('storePreview', [SalesPlanController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [SalesPlanController::class, 'saveImport'])->name('saveImport');
                    });

                    //Sales Plan Additional Costs Import routes
                    Route::group(['prefix' => 'spadcosts/import', 'as' => 'spadcostsImport.'], function () {
                        Route::view('/', 'app.sites.floors.units.sales-plan.import.importspadcosts')->name('importspadcosts');
                        Route::post('preview', [SalesPlanImportController::class, 'ImportPreviewAdcosts'])->name('importspadcostsPreview');
                        Route::get('storePreview', [SalesPlanImportController::class, 'storePreviewAdcosts'])->name('storePreview');
                        Route::post('saveImport', [SalesPlanImportController::class, 'saveImportAdcosts'])->name('saveImport');
                    });


                    //Sales Plan Installments Import routes
                    Route::group(['prefix' => 'spInstallments/import', 'as' => 'spInstallmentsImport.'], function () {
                        Route::view('/', 'app.sites.floors.units.sales-plan.import.importspInstallments')->name('ImportInstallments');
                        Route::post('preview', [SalesPlanImportController::class, 'ImportPreviewinstallments'])->name('ImportPreviewinstallments');
                        Route::get('storePreview', [SalesPlanImportController::class, 'storePreviewInstallments'])->name('storePreviewInstallments');
                        Route::post('saveImport', [SalesPlanImportController::class, 'saveImportInstallments'])->name('saveImportInstallments');
                    });

                    //Units Routes
                    Route::group(['prefix' => '/{floor_id}'], function () {

                        Route::group(['prefix' => 'units', 'as' => 'units.'], function () {
                            Route::get('/', [UnitController::class, 'index'])->name('index');

                            // Unit details by unit_no through AJAX
                            Route::get('/details', [UnitController::class, 'details'])->name('details');

                            Route::get('create', [UnitController::class, 'create'])->name('create');
                            Route::post('store', [UnitController::class, 'store'])->name('store');

                            Route::group(['prefix' => 'bifurcate', 'as' => 'bifurcate.'], function () {
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

                                    // Route::get('create', [SalesPlanController::class, 'create'])->name('create');
                                    // Route::post('store', [SalesPlanController::class, 'store'])->name('store');
                                    Route::post('/approve-sales-plan', [SalesPlanController::class, 'approveSalesPlan'])->name('approve-sales-plan');


                                    Route::post('/disapprove-sales-plan', [SalesPlanController::class, 'disApproveSalesPlan'])->name('disapprove-sales-plan');
                                    Route::get('delete-selected', [SalesPlanController::class, 'destroySelected'])->name('destroy-selected');

                                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                                        // Route::get('generate/installments', [SalesPlanController::class, 'ajaxGenerateInstallments'])->name('generate-installments');
                                        Route::post('/check-stakeholder', [SalesPlanController::class, 'checkStakeholder'])->name('check-stakeholder');
                                    });

                                    Route::group(['prefix' => '/{id}'], function () {

                                        Route::get('edit', [SalesPlanController::class, 'edit'])->name('edit');
                                        Route::get('initail-sales-plan', [SalesPlanController::class, 'initialPreview'])->name('initail-sales-plan');
                                        Route::get('updated-sales-plan', [SalesPlanController::class, 'updatedPreview'])->name('updated-sales-plan');
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

                //Sales Plan Leftbar link
                Route::group(['prefix' => 'sales_plan', 'as' => 'sales_plan.'], function () {
                    // Route::get('/', [SalesPlanController::class, 'inLeftbar'])->name('show');
                    Route::get('create', [SalesPlanController::class, 'create'])->name('create');
                    Route::get('{user_id}/generate/{crm_lead}', [SalesPlanController::class, 'generateSalesPlan'])->name('generateSalesPlan');
                    Route::post('store', [SalesPlanController::class, 'store'])->name('store');
                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        Route::get('generateInstallments', [SalesPlanController::class, 'ajaxGenerateInstallments'])->name('generateInstallments');
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

                    // import types
                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.sites.types.importTypes')->name('importTypes');
                        Route::post('preview', [TypeController::class, 'ImportPreview'])->name('importTypesPreview');
                        Route::get('storePreview', [TypeController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [TypeController::class, 'saveImport'])->name('saveImport');
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

                    // import Stakeholders
                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.sites.stakeholders.importStakeholders')->name('importStakeholders');
                        Route::post('preview', [StakeholderImportController::class, 'ImportPreview'])->name('importStakeholdersPreview');
                        Route::get('storePreview/{type}', [StakeholderImportController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport/{type}', [StakeholderImportController::class, 'saveImport'])->name('saveImport');

                        // import Stakeholder Kins
                        Route::group(['prefix' => 'kins', 'as' => 'kins.'], function () {
                            Route::view('/', 'app.sites.stakeholders.importKins', ['preview' => false, 'final_preview' => false])->name('importStakeholders');
                            Route::post('preview', [StakeholderKinsImportControler::class, 'ImportPreview'])->name('importStakeholdersPreview');
                            Route::get('storePreview', [StakeholderKinsImportControler::class, 'storePreview'])->name('storePreview');
                            Route::post('saveImport', [StakeholderKinsImportControler::class, 'saveImport'])->name('saveImport');
                        });

                        // import Stakeholder Contacts
                        Route::group(['prefix' => 'contacts', 'as' => 'contacts.'], function () {
                            Route::view('/', 'app.sites.stakeholders.import.importContacts', ['preview' => false, 'final_preview' => false])->name('importStakeholders');
                            Route::post('preview', [StakeholderContactsImportControler::class, 'ImportPreview'])->name('importStakeholdersPreview');
                            Route::get('storePreview', [StakeholderContactsImportControler::class, 'storePreview'])->name('storePreview');
                            Route::post('saveImport', [StakeholderContactsImportControler::class, 'saveImport'])->name('saveImport');
                        });
                    });

                    Route::group(['prefix' => '/{id}/ajax', 'as' => 'ajax-'], function () {
                        Route::get('/', [StakeholderController::class, 'ajaxGetById'])->name('get-by-id');
                    });
                });

                // Blacklisted Stakeholders
                Route::group(['prefix' => 'blacklisted-stakeholders', 'as' => 'blacklisted-stakeholders.'], function () {
                    Route::get('/', [BacklistedStakeholderController::class, 'index'])->name('index');


                    Route::get('create', [BacklistedStakeholderController::class, 'create'])->name('create');
                    Route::post('store', [BacklistedStakeholderController::class, 'store'])->name('store');
                    Route::get('delete-selected', [BacklistedStakeholderController::class, 'destroy'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [BacklistedStakeholderController::class, 'edit'])->name('edit');
                        Route::put('update', [BacklistedStakeholderController::class, 'update'])->name('update');
                        Route::get('delete', [BacklistedStakeholderController::class, 'destroy'])->name('destroy');
                    });

                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.sites.stakeholders.importFloors', ['preview' => false, 'final_preview' => false])->name('importStakeholders');
                        Route::post('preview', [BacklistedStakeholderController::class, 'ImportPreview'])->name('importStakeholdersPreview');
                        Route::get('storePreview', [BacklistedStakeholderController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [BacklistedStakeholderController::class, 'saveImport'])->name('saveImport');
                    });

                    Route::group(['prefix' => '/{id}/ajax', 'as' => 'ajax-'], function () {
                        Route::get('/', [BacklistedStakeholderController::class, 'ajaxGetById'])->name('get-by-id');
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

                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.sites.receipts.importReceipts', ['preview' => false])->name('importReceipts');
                        Route::post('preview', [ReceiptController::class, 'ImportPreview'])->name('importReceiptsPreview');
                        Route::get('storePreview', [ReceiptController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [ReceiptController::class, 'saveImport'])->name('saveImport');
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
                    Route::get('revert-payment/{ids}', [ReceiptController::class, 'revertPayment'])->name('revert-payment');

                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('show', [ReceiptController::class, 'show'])->name('show');
                        // Route::get('edit', [ReceiptController::class, 'edit'])->name('edit');
                        // Route::put('update', [ReceiptController::class, 'update'])->name('update');
                        // Route::get('delete', [ReceiptController::class, 'destroy'])->name('destroy');
                    });
                });

                // File Transfer Receipts
                Route::group(['prefix' => 'file-transfer-receipts', 'as' => 'file-transfer-receipts.'], function () {
                    Route::get('/', [TransferReceiptController::class, 'index'])->name('index');

                    Route::get('create', [TransferReceiptController::class, 'create'])->name('create');
                    Route::post('store', [TransferReceiptController::class, 'store'])->name('store');

                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        Route::post('get-transfer-file-data', [TransferReceiptController::class, 'getTransferFileData'])->name('get-transfer-file-data');
                    });

                    Route::group(['prefix' => '/{receipts_id}'], function () {
                        Route::group(['prefix' => 'templates', 'as' => 'templates.'], function () {
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('/print', [TransferReceiptController::class, 'printReceipt'])->name('print');
                            });
                        });
                    });

                    Route::get('destroy-draft', [TransferReceiptController::class, 'destroyDraft'])->name('destroy-draft');
                    Route::get('delete-selected', [TransferReceiptController::class, 'destroySelected'])->name('destroy-selected');
                    Route::get('make-active-selected', [TransferReceiptController::class, 'makeActiveSelected'])->name('make-active-selected');
                    Route::get('revert-payment/{ids}', [TransferReceiptController::class, 'revertPayment'])->name('revert-payment');

                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('show', [TransferReceiptController::class, 'show'])->name('show');
                    });
                });

                // Banks
                Route::group(['prefix' => 'banks', 'as' => 'banks.'], function () {
                    Route::get('/', [BankController::class, 'index'])->name('index');

                    Route::get('create', [BankController::class, 'create'])->name('create');
                    Route::post('store', [BankController::class, 'store'])->name('store');

                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.sites.banks.importBanks', ['preview' => false])->name('importBanks');
                        Route::post('preview', [BankController::class, 'ImportPreview'])->name('importBanksPreview');
                        Route::get('storePreview', [BankController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [BankController::class, 'saveImport'])->name('saveImport');
                    });

                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        // used for bank selection in receipt creation
                        Route::post('get-by-id', [BankController::class, 'getBank'])->name('get-by-id');
                    });
                });

                // Payment Vouchers
                Route::group(['prefix' => 'payment-voucher', 'as' => 'payment-voucher.'], function () {
                    Route::get('/', [PaymentVocuherController::class, 'index'])->name('index');

                    Route::get('create', [PaymentVocuherController::class, 'create'])->name('create');
                    Route::post('store', [PaymentVocuherController::class, 'store'])->name('store');
                    // Route::get('approve/{rebate_incentive_id}', [RebateIncentiveController::class, 'approve'])->name('approve');

                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('show', [PaymentVocuherController::class, 'show'])->name('show');
                        Route::get('active-cheque', [PaymentVocuherController::class, 'activeCheque'])->name('active-cheque');
                        Route::get('approve', [PaymentVocuherController::class, 'approvePaymentVoucher'])->name('approve');
                    });


                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        Route::post('get-accounts-payable-data', [PaymentVocuherController::class, 'getAccountsPayableData'])->name('get-accounts-payable-data');
                    });
                });

                // File Management
                Route::group(['prefix' => 'file-managements', 'as' => 'file-managements.'], function () {

                    Route::get('/customers', [FileManagementController::class, 'customers'])->name('customers');
                    Route::get('/view-files', [FileManagementController::class, 'viewFiles'])->name('view-files');

                    // import files
                    Route::group(['prefix' => 'import'], function () {
                        Route::view('/', 'app.sites.file-managements.import.importFiles', ['preview' => false])->name('importFiles');
                        Route::post('preview', [FileManagementController::class, 'ImportPreview'])->name('importFilesPreview');
                        Route::get('storePreview', [FileManagementController::class, 'storePreview'])->name('storePreview');
                        Route::post('saveImport', [FileManagementController::class, 'saveImport'])->name('saveImport');

                        // import files Conatcts
                        Route::group(['prefix' => 'contacts'], function () {
                            Route::view('/', 'app.sites.file-managements.import.importFilesContacts', ['preview' => false])->name('importFilesContacts');
                            Route::post('preview', [FileManagementController::class, 'ImportContactsPreview'])->name('importFilesContactsPreview');
                            Route::get('storePreview', [FileManagementController::class, 'storeContactsPreview'])->name('storeFileContactsPreview');
                            Route::post('saveImport', [FileManagementController::class, 'saveFileContactsImport'])->name('saveFileContactsImport');
                        });
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
                    // rebate incentive form
                    Route::group(['prefix' => 'rebate-incentive', 'as' => 'rebate-incentive.'], function () {

                        Route::get('/', [RebateIncentiveController::class, 'index'])->name('index');

                        Route::get('create', [RebateIncentiveController::class, 'create'])->name('create');
                        Route::post('store', [RebateIncentiveController::class, 'store'])->name('store');
                        Route::group(['prefix' => '/{id}'], function () {
                            Route::get('edit', [RebateIncentiveController::class, 'edit'])->name('edit');
                            Route::put('update', [RebateIncentiveController::class, 'update'])->name('update');
                        });
                        Route::get('approve/{rebate_incentive_id}', [RebateIncentiveController::class, 'approve'])->name('approve');

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
                });

                // Accounts Routes
                Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {

                    // Route::get('/', [AdditionalCostController::class, 'index'])->name('index');

                    // Accounts Recovery Routes
                    Route::group(['prefix' => 'recovery', 'as' => 'recovery.'], function () {
                        Route::get('/dashboard', [AccountsRecoveryController::class, 'dashboard'])->name('dashboard');
                        Route::get('/calender', [AccountsRecoveryController::class, 'calender'])->name('calender');
                        Route::get('/inventory-aging', [AccountsRecoveryController::class, 'inventoryAging'])->name('inventory-aging');
                        Route::get('/sales-plans', [AccountsRecoveryController::class, 'salesPlan'])->name('salesPlan');

                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::post('get-filtered-calender-events', [AccountsRecoveryController::class, 'getFilteredUnitData'])->name('get-filtered-calender-events');
                            Route::post('filter-inventory-aging', [AccountsRecoveryController::class, 'filterInventoryAging'])->name('filter-inventory-aging');
                        });
                    });

                    // Charts Of accounts
                    Route::group(['prefix' => 'charts-of-accounts', 'as' => 'charts-of-accounts.'], function () {
                        Route::get('/', [ChartsOfAccountsController::class, 'index'])->name('index');
                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::post('get-fourth-level-accounts', [ChartsOfAccountsController::class, 'getFourthLevelAccounts'])->name('get-fourth-level-accounts');
                            Route::post('get-fifth-level-accounts', [ChartsOfAccountsController::class, 'getFifthLevelAccounts'])->name('get-fifth-level-accounts');
                            // calculae balance
                            Route::post('get-first-level-balance', [ChartsOfAccountsController::class, 'getFirstLevelBalance'])->name('get-first-level-balance');
                            Route::post('get-second-level-balance', [ChartsOfAccountsController::class, 'getSecondLevelBalance'])->name('get-second-level-balance');
                            Route::post('get-third-level-balance', [ChartsOfAccountsController::class, 'getThirdLevelBalance'])->name('get-third-level-balance');
                        });
                    });
                    //trial-balance
                    Route::group(['prefix' => 'trial-balance', 'as' => 'trial-balance.'], function () {
                        Route::get('/', [TrialBalanceController::class, 'index'])->name('index');
                        Route::get('/filter-trial-blance/{account_head_code_id}', [TrialBalanceController::class, 'filter'])->name('filter-trial-blance');
                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::post('get-fourth-level-accounts', [ChartsOfAccountsController::class, 'getFourthLevelAccounts'])->name('get-fourth-level-accounts');
                            Route::post('get-fifth-level-accounts', [ChartsOfAccountsController::class, 'getFifthLevelAccounts'])->name('get-fifth-level-accounts');
                            // calculae balance
                            Route::post('get-first-level-balance', [ChartsOfAccountsController::class, 'getFirstLevelBalance'])->name('get-first-level-balance');
                            Route::post('get-second-level-balance', [ChartsOfAccountsController::class, 'getSecondLevelBalance'])->name('get-second-level-balance');
                            Route::post('get-third-level-balance', [ChartsOfAccountsController::class, 'getThirdLevelBalance'])->name('get-third-level-balance');
                        });
                    });
                    //trial-balance / General Ledger

                    Route::group(['prefix' => 'general-ledger', 'as' => 'general-ledger.'], function () {
                        Route::get('/', [GeneralLedgerController::class, 'index'])->name('index');
                        Route::get('/filter-trial-blance/{account_head_code_id}', [GeneralLedgerController::class, 'filter'])->name('filter-trial-blance');
                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::Post('filter-data-trial-balance', [GeneralLedgerController::class, 'filterTrialBalance'])->name('filter-data-trial-balance');
                        });
                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::Post('filter-by-user-data-trial-balance', [GeneralLedgerController::class, 'filterByDate'])->name('filter-by-user-data-trial-balance');
                        });
                    });
                    // Accounts ledger
                    Route::group(['prefix' => 'ledger', 'as' => 'ledger.'], function () {
                        Route::get('/', [LedgerController::class, 'index'])->name('index');
                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::get('get-refund-datatable', [LedgerController::class, 'refundDatatable'])->name('get-refund-datatable');
                        });
                    });
                    // Journal Entries
                    Route::group(['prefix' => 'journal-entry', 'as' => 'journal-entry.'], function () {
                        Route::get('/', [JournalEntryController::class, 'index'])->name('index');
                        Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                            Route::get('get-refund-datatable', [JournalEntryController::class, 'refundDatatable'])->name('get-refund-datatable');
                        });
                    });
                });

                // Stakeholder Investors Deals
                Route::group(['prefix' => 'investors-deals', 'as' => 'investors-deals.'], function () {
                    Route::get('/', [StakeholderInvestorController::class, 'index'])->name('index');
                    Route::get('create', [StakeholderInvestorController::class, 'create'])->name('create');
                    Route::post('store', [StakeholderInvestorController::class, 'store'])->name('store');

                    Route::get('destroy-selected', [StakeholderInvestorController::class, 'destroySelected'])->name('destroy-selected');

                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [StakeholderInvestorController::class, 'edit'])->name('edit');
                        Route::put('update', [StakeholderInvestorController::class, 'update'])->name('update');
                        Route::get('preview', [StakeholderInvestorController::class, 'show'])->name('preview');
                        Route::get('check-investor', [StakeholderInvestorController::class, 'checkInvestor'])->name('check-investor');
                        Route::get('approve-investor', [StakeholderInvestorController::class, 'approveInvestor'])->name('approve-investor');
                        Route::get('revert-investor', [StakeholderInvestorController::class, 'revertInvestor'])->name('revert-investor');
                        Route::get('dis-approve-investor', [StakeholderInvestorController::class, 'disapproveInvestor'])->name('dis-approve-investor');
                    });
                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        Route::post('get-units-data', [StakeholderInvestorController::class, 'getUnitsData'])->name('get-units-data');
                    });
                });

                // Invstor Deals Receipts
                Route::group(['prefix' => 'investor-deals-receipts', 'as' => 'investor-deals-receipts.'], function () {
                    Route::get('/', [InvsetorDealsReceiptController::class, 'index'])->name('index');

                    Route::get('create', [InvsetorDealsReceiptController::class, 'create'])->name('create');
                    Route::post('store', [InvsetorDealsReceiptController::class, 'store'])->name('store');

                    Route::group(['prefix' => '/ajax', 'as' => 'ajax-'], function () {
                        Route::post('get-investor-deals-data', [InvsetorDealsReceiptController::class, 'getInvestorDealsData'])->name('get-investor-deals-data');
                    });

                    Route::group(['prefix' => '/{receipts_id}'], function () {
                        Route::group(['prefix' => 'templates', 'as' => 'templates.'], function () {
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('/print', [InvsetorDealsReceiptController::class, 'printReceipt'])->name('print');
                            });
                        });
                    });

                    Route::get('destroy-draft', [InvsetorDealsReceiptController::class, 'destroyDraft'])->name('destroy-draft');
                    Route::get('delete-selected', [InvsetorDealsReceiptController::class, 'destroySelected'])->name('destroy-selected');
                    Route::get('make-active-selected', [InvsetorDealsReceiptController::class, 'makeActiveSelected'])->name('make-active-selected');
                    Route::get('revert-payment/{ids}', [InvsetorDealsReceiptController::class, 'revertPayment'])->name('revert-payment');

                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('show', [InvsetorDealsReceiptController::class, 'show'])->name('show');
                    });
                });

                Route::get('import/sample-download/{order}', [AdditionalCostController::class, 'downloadSample'])->name('import.sample-download');
            });
        });

        Route::get('ajax-get-unit-input', [UnitController::class, 'getUnitInput'])->name('ajax-unit.get.input');
        Route::get('ajax-draw-facing-field', [UnitController::class, 'drawFacingField'])->name('ajax-facing.field.draw');
        Route::get('ajax-update-unit-name', [UnitController::class, 'updateUnitName'])->name('ajax-unit.name.update');

        Route::get('ajax-import-floor.get.input', [FloorController::class, 'getUnitInput'])->name('ajax-import-floor.get.input');
        Route::get('ajax-import-floor.error.inputs', [FloorController::class, 'UpdateErrorInput'])->name('ajax-import-floor.error.inputs');
        Route::get('ajax-import-stakeholders.get.input', [StakeholderImportController::class, 'getInput'])->name('ajax-import-stakeholders.get.input');
        Route::get('ajax-import-types.get.input', [TypeController::class, 'getTypeInput'])->name('ajax-import-types.get.input');
        Route::get('ajax-import-additional-costs.get.input', [AdditionalCostController::class, 'getInput'])->name('ajax-import-additional-costs.get.input');
        Route::get('ajax-import-units.get.input', [UnitController::class, 'getInput'])->name('ajax-import-units.get.input');
        Route::get('ajax-import-sales-plan.get.input', [SalesPlanController::class, 'getInput'])->name('ajax-import-sales-plan.get.input');
        Route::get('ajax-import-sales-plan.adCosts.get.input', [SalesPlanImportController::class, 'getInputAdcosts'])->name('ajax-import-sales-plan.adCosts.get.input');
        Route::get('ajax-import-sales-plan.installments.get.input', [SalesPlanImportController::class, 'getInputInstallments'])->name('ajax-import-sales-plan.installments.get.input');
        Route::get('ajax-import-receipts.get.input', [ReceiptController::class, 'getInput'])->name('ajax-import-receipts.get.input');
        Route::get('ajax-import-banks.get.input', [BankController::class, 'getInput'])->name('ajax-import-banks.get.input');
        Route::get('ajax-import-stakeholders.kins.get.input', [StakeholderKinsImportControler::class, 'getInput'])->name('ajax-import-stakeholders.kins.get.input');
        Route::post('ajax-get-unit', [SalesPlanController::class, 'getUnitDetails'])->name('ajax-get-unit');

        Route::post('ajax-import-image/save-file', [ImageImportController::class, 'saveFile'])->name('ajax-import-image.save-file');
        Route::delete('ajax-import-image/revert-file', [ImageImportController::class, 'revertFile'])->name('ajax-import-image.revert-file');
        Route::post('ajax-import-image/delete-file', [ImageImportController::class, 'deleteFile'])->name('ajax-import-image.delete-file');

        Route::post('ajax-get-cities/{stateId}', [CityController::class, 'getCities'])->name('ajax-get-cities');
        Route::post('ajax-get-states/{countryId}', [StateController::class, 'getStates'])->name('ajax-get-states');
        Route::post('ajax-get-stakeholder_types/{stakeholderId}', [PaymentVocuherController::class, 'stakeholder_types'])->name('ajax-get-stakeholder_types');

        //Countries Routes
        Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {
            Route::get('cities', [CountryController::class, 'getCities'])->name('cities');
        });

        Route::group(['prefix' => 'batches', 'as' => 'batches.'], function () {
            Route::get('clear-all', [JobBatchController::class, 'clearAllQueues'])->name('clear-all');
            Route::get('/{batch_id}', [JobBatchController::class, 'getJobBatchByID'])->name('byid');
        });

        Route::group(['prefix' => 'commands', 'as' => 'commands.'], function () {
            Route::get('/{command}', [ArtisanCommandController::class, 'commands'])->name('command');
        });
    });
    Route::get('download-investment-plan/{file_name}', [SalesPlanController::class, 'downloadInvestmentPlan'])->name('download-investment-plan');

    Route::get('download-payment-plan/{file_name}', [SalesPlanController::class, 'downloadPaymentPlan'])->name('download-payment-plan');

    // authorize Stakeholder
    Route::get('authorize-stakeholder/{file_name}', [StakeholderController::class, 'authorizeStakeholder'])->name('authorize-stakeholder');
    Route::post('authorize-stakeholder/{file_name}/{stakeholder_id}', [StakeholderController::class, 'verifyPin'])->name('verifyPin');
});

Route::group(['prefix' => 'tests'], function () {
    Route::get('test', [testController::class, 'test'])->name('test');
    Route::get('jobs', [testController::class, 'jobs'])->name('jobs');
    Route::get('/batch/{batchId}', [testController::class, 'getBatchByID'])->name('batch');
    Route::get('/session/{batchId}', [testController::class, 'setBatchIDInSession'])->name('sbatch');
    Route::get('/session/{batchId}/remove', [testController::class, 'unsetBatchIDInSession'])->name('ssbatch');
    Route::get('activitylogs', [testController::class, 'activityLog']);
    Route::get('/createaccount', [testController::class, 'createAccount']);
});

Route::get('/read-all-notifications', [NotificationController::class, 'readAllNotifications']);
Route::post('/read-single-notification', [NotificationController::class, 'readSingleNotification']);

Route::get('/logs', function () {
    return Activity::latest()->get();
});


Route::get('/deletedPdfs/{sure}', function ($sure) {
    if ($sure) {
        foreach (File::glob(public_path('app-assets/pdf/sales-plans/investment-plan/*')) as $key => $path) {
            $test = File::delete($path);
        }
        foreach (File::glob(public_path('app-assets/pdf/sales-plans/payment-plan/*')) as $key => $path) {
            $test = File::delete($path);
        }
        foreach (File::glob(public_path('app-assets/pdf/sales-plans/qrcodes/*')) as $key => $path) {
            $test = File::delete($path);
        }
        return $test;
    }
})->name('deletedPdfs');
// Route::get('/recoverTypes', function(){
//     $types = Type::withTrashed()->forceDelete();
//     return $types;
// });
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
