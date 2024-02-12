<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\AdminCategoryController;
use Modules\Category\Http\Controllers\PublicCategoryController;
use Modules\Role\Helpers\PermissionHelper;

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

Route::group(['prefix' => 'admin/categories',  'middleware' => [GeneralHelper::authMiddleware()]], function () {
    Route::get('', [AdminCategoryController::class, 'index'])
        ->middleware(PermissionHelper::getPermissionNameMiddleware('all', 'category'));

    Route::post('', [AdminCategoryController::class, 'store'])
        ->middleware(PermissionHelper::getPermissionNameMiddleware('store', 'category'));

    Route::get('{id}', [AdminCategoryController::class, 'show'])
        ->middleware(PermissionHelper::getPermissionNameMiddleware('show', 'category'));

    Route::post('{id}', [AdminCategoryController::class, 'update'])
        ->middleware(PermissionHelper::getPermissionNameMiddleware('update', 'category'));

    Route::delete('{id}', [AdminCategoryController::class, 'destroy'])
        ->middleware(PermissionHelper::getPermissionNameMiddleware('delete', 'category'));
});

Route::group(['prefix' => 'users/categories'], function () {
    Route::get('', [PublicCategoryController::class, 'index']);
    Route::get('{id}', [PublicCategoryController::class, 'show']);
});

Route::get('mobile/categories', [PublicCategoryController::class, 'index']);
