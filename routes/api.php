<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// login or generate token
Route::post('login', [AuthController::class, 'login']);
Route::get('checkAuth', [AuthController::class, 'checkAuth']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => '/auth'], function () {
        // save lead as stakeholder in erp
        Route::post('saveLead', [LeadController::class, 'saveLead']);

        // Route::get('logout', [AuthController::class, 'logout']);
    });
});
