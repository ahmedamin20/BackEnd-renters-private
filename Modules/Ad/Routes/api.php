<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Ad\Http\Controllers\AdminAdController;
use Modules\Ad\Http\Controllers\PublicAdController;

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

Route::group(['prefix' => 'admin/ads', 'middleware' => [GeneralHelper::authMiddleware()]], function () {
    Route::get('', [AdminAdController::class, 'index'])
        ->middleware(['test_middleware']);
    Route::post('', [AdminAdController::class, 'store']);
    Route::get('{ad}', [AdminAdController::class, 'show']);
    Route::post('{ad}', [AdminAdController::class, 'update']);
    Route::delete('{ad}', [AdminAdController::class, 'destroy']);
});

Route::group(['prefix' => 'users/ads'], function () {
    Route::get('', [PublicAdController::class, 'index']);
});
