<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\AboutUs\Http\Controllers\AdminAboutUsController;
use Modules\AboutUs\Http\Controllers\PublicAboutUsController;

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

Route::group(['prefix' => 'admin/about_us', 'middleware' => [GeneralHelper::authMiddleware()]], function () {
    Route::get('', [AdminAboutUsController::class, 'show']);
    Route::post('', [AdminAboutUsController::class, 'update']);
});

Route::get('users/about_us', [PublicAboutUsController::class, 'show']);
