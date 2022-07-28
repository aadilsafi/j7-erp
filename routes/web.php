<?php

use App\Http\Controllers\{
    AdditionalCostController,
    DashboardController,
    RoleController,
    PermissionController,
    TypeController,
    SiteController,
    CountryController,
    FloorController,
    UnitController,
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

// Route::group(['domain' => '*.localhost:8000' ], function($subdomain) {
//     Route::get('/', function () use ($subdomain) {
//         return $subdomain;
//     });

// });

Route::group([
    // 'prefix' => LaravelLocalization::setLocale(),
    // 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    Route::get('/', function () {
        return redirect()->route('login.view');
    });

    Route::group(['middleware' => ['auth', 'permission']], function () {
        // Route::group(['middleware' => ['auth']], function () {

        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::get('cachew/flush', [DashboardController::class, 'cacheFlush'])->name('site.cache.flush');

        //Role Routes
        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');

            Route::get('create', [RoleController::class, 'create'])->name('create');
            Route::post('store', [RoleController::class, 'store'])->name('store');

            Route::get('delete-selected', [RoleController::class, 'destroySelected'])->name('destroy.selected');
            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [RoleController::class, 'edit'])->name('edit');
                Route::post('update', [RoleController::class, 'update'])->name('update');

                Route::get('delete', [RoleController::class, 'destroy'])->name('destroy');
                Route::get('make-default', [RoleController::class, 'makeDefault'])->name('make-default');
            });
        });

        //Permissions Routes
        Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');

            Route::get('create', [PermissionController::class, 'create'])->name('create');
            Route::post('store', [PermissionController::class, 'store'])->name('store');

            Route::get('delete-selected', [PermissionController::class, 'destroySelected'])->name('destroy.selected');
            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [PermissionController::class, 'edit'])->name('edit');
                Route::put('update', [PermissionController::class, 'update'])->name('update');

                Route::get('delete', [PermissionController::class, 'destroy'])->name('destroy');
            });

            // Route::get('role-has-permission', [PermissionController::class, 'roleHasPermission']);
            // Route::get('refresh-permissions', [PermissionController::class, 'refreshPermissions']);

            Route::post('assign-permission', [PermissionController::class, 'assignPermissionToRole'])->name('assign-permission');
            Route::post('revoke-permission', [PermissionController::class, 'revokePermissionToRole'])->name('revoke-permission');
        });

        //Sites Routes
        Route::group(['prefix' => 'sites', 'as' => 'sites.'], function () {
            Route::get('/', [SiteController::class, 'index'])->name('index');

            Route::get('create', [SiteController::class, 'create'])->name('create');
            Route::post('store', [SiteController::class, 'store'])->name('store');

            Route::get('delete-selected', [SiteController::class, 'destroySelected'])->name('destroy.selected');

            Route::group(['prefix' => 'configurations/{id}', 'as' => 'configurations.'], function () {
                Route::get('/', [SiteController::class, 'configView'])->name('configView');
                Route::post('store', [SiteController::class, 'configStore'])->name('configStore');
            });

            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [SiteController::class, 'edit'])->name('edit');
                Route::put('update', [SiteController::class, 'update'])->name('update');
                Route::get('delete', [SiteController::class, 'destroy'])->name('destroy');
            });

            Route::group(['prefix' => '/{site_id}'], function () {

                //Additional Costs Routes
                Route::group(['prefix' => 'additional-costs', 'as' => 'additional-costs.'], function () {
                    Route::get('/', [AdditionalCostController::class, 'index'])->name('index');

                    Route::get('create', [AdditionalCostController::class, 'create'])->name('create');
                    Route::post('store', [AdditionalCostController::class, 'store'])->name('store');

                    Route::get('delete-selected', [AdditionalCostController::class, 'destroySelected'])->name('destroy.selected');
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

                    Route::get('delete-selected', [FloorController::class, 'destroySelected'])->name('destroy.selected');
                    Route::group(['prefix' => '/{id}'], function () {
                        Route::get('edit', [FloorController::class, 'edit'])->name('edit');
                        Route::put('update', [FloorController::class, 'update'])->name('update');
                    });

                    // //Units Routes
                    Route::group(['prefix' => '/{floor_id}'], function () {
                        Route::group(['prefix' => 'units', 'as' => 'units.'], function () {
                            Route::get('/', [UnitController::class, 'index'])->name('index');

                            Route::get('create', [UnitController::class, 'create'])->name('create');
                            Route::post('store', [UnitController::class, 'store'])->name('store');

                            Route::get('delete-selected', [UnitController::class, 'destroySelected'])->name('destroy.selected');
                            Route::group(['prefix' => '/{id}'], function () {
                                Route::get('edit', [UnitController::class, 'edit'])->name('edit');
                                Route::put('update', [UnitController::class, 'update'])->name('update');
                            });
                        });
                    });
                });
            });
        });

        //Countries Routes
        Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {
            Route::get('cities', [CountryController::class, 'getCities'])->name('cities');
        });

        //Types Routes
        Route::group(['prefix' => 'types', 'as' => 'types.'], function () {
            Route::get('/', [TypeController::class, 'index'])->name('index');

            Route::get('create', [TypeController::class, 'create'])->name('create');
            Route::post('store', [TypeController::class, 'store'])->name('store');

            Route::get('delete-selected', [TypeController::class, 'destroySelected'])->name('destroy.selected');
            Route::group(['prefix' => '/{id}'], function () {
                Route::get('edit', [TypeController::class, 'edit'])->name('edit');
                Route::put('update', [TypeController::class, 'update'])->name('update');

                Route::get('delete', [TypeController::class, 'destroy'])->name('destroy');
            });
        });

        // //Additional Costs Routes
        // Route::group(['prefix' => 'additional-costs', 'as' => 'additional-costs.'], function () {
        //     Route::get('/', [AdditionalCostController::class, 'index'])->name('index');

        //     Route::get('create', [AdditionalCostController::class, 'create'])->name('create');
        //     Route::post('store', [AdditionalCostController::class, 'store'])->name('store');

        //     Route::get('delete-selected', [AdditionalCostController::class, 'destroySelected'])->name('destroy.selected');
        //     Route::group(['prefix' => '/{id}'], function () {
        //         Route::get('edit', [AdditionalCostController::class, 'edit'])->name('edit');
        //         Route::put('update', [AdditionalCostController::class, 'update'])->name('update');

        //         Route::get('delete', [AdditionalCostController::class, 'destroy'])->name('destroy');
        //     });
        // });
    });
});
