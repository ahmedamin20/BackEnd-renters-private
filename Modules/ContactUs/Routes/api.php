<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\ContactUs\Http\Controllers\AdminContactUsController;
use Modules\ContactUs\Http\Controllers\PublicContactUsController;

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

Route::group(['prefix' => 'admin/contact_us', 'middleware' => [GeneralHelper::authMiddleware()]], function () {
    Route::get('', [AdminContactUsController::class, 'index']);
    Route::delete('{id}', [AdminContactUsController::class, 'destroy']);
    Route::patch('{id}', [AdminContactUsController::class, 'changeStatus']);
});

Route::group(['prefix' => 'users/contact_us'], function () {
    Route::get('', [PublicContactUsController::class, 'index']);
    Route::post('', [PublicContactUsController::class, 'store']);
});
