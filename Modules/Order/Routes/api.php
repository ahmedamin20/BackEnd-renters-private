<?php

use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\AdminOrderController;
use Modules\Order\Http\Controllers\OrderController;

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

Route::group(['middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function(){
    Route::post('orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::post('orders/{id}/accept', [OrderController::class, 'accept']);
    Route::post('orders/{id}/reject', [OrderController::class, 'reject']);
    Route::apiResource('orders', OrderController::class)
        ->only(['index', 'show', 'store']);
});

Route::group(['prefix' => 'admin/orders'], function(){
   Route::get('', [AdminOrderController::class, 'index']);
   Route::get('{id}', [AdminOrderController::class, 'show']);
});
