<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\ClientController;
use Modules\User\Http\Controllers\UserController;

Route::group(['prefix' => 'admin/users', 'middleware' => [GeneralHelper::authMiddleware()]], function () {
    Route::get('', [UserController::class, 'index']);
    Route::post('', [UserController::class, 'store']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::post('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
    Route::patch('{user}', [UserController::class, 'changeStatus']);
});

Route::group(['prefix' => 'admin/clients'], function(){
   Route::get('', [ClientController::class, 'index']);
   Route::get('{id}', [ClientController::class, 'show']);
   Route::patch('{id}', [ClientController::class, 'changeStatus']);
});
