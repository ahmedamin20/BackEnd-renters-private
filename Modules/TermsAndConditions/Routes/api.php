<?php

use Illuminate\Support\Facades\Route;
use Modules\TermsAndConditions\Http\Controllers\TermConditionController;

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

Route::group(['prefix' => 'terms_and_conditions'], function () {
    Route::get('', [TermConditionController::class, 'show']);
    Route::put('', [TermConditionController::class, 'update']);
});

Route::get('public/terms_and_conditions', [TermConditionController::class, 'show']);
