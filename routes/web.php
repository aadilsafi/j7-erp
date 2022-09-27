<?php

use App\Http\Controllers\{
    AdditionalCostController,
    ArtisanCommandController,
    DashboardController,
    RoleController,
    PermissionController,
    TypeController,
    SiteController,
    CountryController,
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
};
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

            Route::get('delete-selected', [RoleController::class, 'destroySelected'])->name('destroy-selected');
            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [RoleController::class, 'edit'])->name('edit');
                Route::put('update', [RoleController::class, 'update'])->name('update');

                Route::get('delete', [RoleController::class, 'destroy'])->name('destroy');
                Route::get('make-default', [RoleController::class, 'makeDefault'])->name('make-default');
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

                    // //Units Routes
                    Route::group(['prefix' => '/{floor_id}'], function () {

                        Route::group(['prefix' => 'units', 'as' => 'units.'], function () {
                            Route::get('/', [UnitController::class, 'index'])->name('index');

                            Route::get('create', [UnitController::class, 'create'])->name('create');
                            Route::post('store', [UnitController::class, 'store'])->name('store');

                            Route::get('preview', [UnitController::class, 'preview'])->name('preview');
                            Route::get('save-changes', [UnitController::class, 'saveChanges'])->name('changes.save');

                            Route::get('delete-selected', [UnitController::class, 'destroySelected'])->name('destroy-selected');

                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [UnitController::class, 'edit'])->name('edit');
                                Route::put('update', [UnitController::class, 'update'])->name('update');
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

                                        // Receipts Routes

                                        Route::group(['prefix' => 'receipts', 'as' => 'receipts.'], function () {

                                            Route::get('/', [ReceiptController::class, 'index'])->name('index');

                                            Route::get('create', [ReceiptController::class, 'create'])->name('create');
                                            Route::post('store', [ReceiptController::class, 'store'])->name('store');


                                            Route::get('delete-selected', [ReceiptController::class, 'destroySelected'])->name('destroy-selected');


                                            Route::group(['prefix' => '/{id}'], function () {

                                                Route::get('edit', [ReceiptController::class, 'edit'])->name('edit');
                                                Route::put('update', [ReceiptController::class, 'update'])->name('update');
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


                    Route::get('delete-selected', [ReceiptController::class, 'destroySelected'])->name('destroy-selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [ReceiptController::class, 'edit'])->name('edit');
                        Route::put('update', [ReceiptController::class, 'update'])->name('update');
                        Route::get('delete', [ReceiptController::class, 'destroy'])->name('destroy');
                    });
                });

                // File Management
                Route::group(['prefix' => 'file-managements', 'as' => 'file-managements.'], function () {

                    Route::get('/customers', [FileManagementController::class, 'customers'])->name('customers');

                    Route::group(['prefix' => 'customers/{customer_id}', 'as' => 'customers.'], function () {
                        Route::get('/units', [FileManagementController::class, 'units'])->name('units');

                        Route::group(['prefix' => 'units/{unit_id}', 'as' => 'units.'], function () {

                            //Files Routes
                            Route::group(['prefix' => 'files', 'as' => 'files.'], function () {
                                Route::get('/', [FileManagementController::class, 'index'])->name('index');

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
            });
        });

        Route::get('ajax-get-unit-input', [UnitController::class, 'getUnitInput'])->name('ajax-unit.get.input');
        Route::get('ajax-draw-facing-field', [UnitController::class, 'drawFacingField'])->name('ajax-facing.field.draw');
        Route::get('ajax-update-unit-name', [UnitController::class, 'updateUnitName'])->name('ajax-unit.name.update');

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
});

Route::get('/read-all-notifications', [NotificationController::class, 'readAllNotifications']);
Route::post('/read-single-notification', [NotificationController::class, 'readSingleNotification']);

Route::get('/print-receipts', [ReceiptController::class, 'printReceipt']);
