<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\AdminSettingController;
use Modules\Setting\Http\Controllers\PublicSettingController;

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

Route::group(['prefix' => 'admin/settings', 'middleware' => [GeneralHelper::authMiddleware()]], function () {
    Route::get('', [AdminSettingController::class, 'show']);
    Route::put('', [AdminSettingController::class, 'update']);
});

Route::group(['prefix' => 'users/settings'], function () {
    Route::get('', [PublicSettingController::class, 'show']);
});
