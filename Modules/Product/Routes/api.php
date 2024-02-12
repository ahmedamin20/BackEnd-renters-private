<?php

use App\Helpers\GeneralHelper;
use App\Helpers\UserHelper;
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\AdminProductController;
use Modules\Product\Http\Controllers\ClientProductController;
use Modules\Product\Http\Controllers\PublicProductController;

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

Route::group(['prefix' => 'client/products', 'middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function(){
    Route::get('', [ClientProductController::class, 'index']);
    Route::get('{product}', [ClientProductController::class, 'show']);
    Route::post('', [ClientProductController::class, 'store']);
    Route::post('{product}', [ClientProductController::class, 'update']);
    Route::delete('{product}', [ClientProductController::class, 'destroy']);
});
Route::group(['prefix' => 'public/products'], function(){
    Route::get('', [PublicProductController::class, 'index']);
    Route::get('{product}', [PublicProductController::class, 'show'])
        ->whereNumber('product');
});

Route::group(['prefix' => 'admin/products', 'middleware' => GeneralHelper::getDefaultLoggedUserMiddlewares()], function(){
    Route::get('', [AdminProductController::class, 'index']);
    Route::get('{id}', [AdminProductController::class, 'show']);
});
