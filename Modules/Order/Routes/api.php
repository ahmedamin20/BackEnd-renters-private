<?php

use App\Helpers\GeneralHelper;
use App\Helpers\UserHelper;
use Modules\Order\Http\Controllers\AgencyOrderController;
use Modules\Order\Http\Controllers\ClientOrderController;

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

Route::group(['middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function () {
    Route::group(['prefix' => 'client', 'middleware' => GeneralHelper::userTypeIn([UserHelper::clientType()])], function () {
        Route::get('', [ClientOrderController::class, 'index']);
        Route::get('{order}', [ClientOrderController::class, 'show'])
            ->whereNumber('order');
        Route::post('', [ClientOrderController::class, 'store']);
        Route::delete('{order}', [ClientOrderController::class, 'destroy'])
            ->whereNumber('order');
    });

    Route::group(['prefix' => 'agency', 'middleware' => GeneralHelper::userTypeIn([UserHelper::agencyType()])], function () {
        Route::get('', [AgencyOrderController::class, 'index']);
        Route::get('{order}', [AgencyOrderController::class, 'show'])
            ->whereNumber('order');
        Route::patch('approve_order/{order}', [AgencyOrderController::class, 'approveOrder'])
            ->whereNumber('order');
        Route::patch('cancel_order/{order}', [AgencyOrderController::class, 'cancelOrder'])
            ->whereNumber('order');
    });
});
