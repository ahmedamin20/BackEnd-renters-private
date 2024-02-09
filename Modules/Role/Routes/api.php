<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\Helpers\PermissionHelper;
use Modules\Role\Http\Controllers\RoleController;

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
// All routes are mapped in RouteServiceProvider to roles

Route::group(['as' => 'role.'], function () {
    Route::get('', [RoleController::class, 'index']);
    //        ->middleware(PermissionHelper::getPermissionNameMiddleware('all', 'role'));

    Route::post('', [RoleController::class, 'store']);
    //->middleware(PermissionHelper::getPermissionNameMiddleware('store', 'role'));;

    Route::put('{role}', [RoleController::class, 'update'])
        ->whereNumber('role');
    //->middleware(PermissionHelper::getPermissionNameMiddleware('update', 'role'));

    Route::get('{role}', [RoleController::class, 'show'])
        ->whereNumber('role');
    //->middleware(PermissionHelper::getPermissionNameMiddleware('show', 'role'));

    Route::delete('{role}', [RoleController::class, 'destroy'])
        ->whereNumber('role');
    //->middleware(PermissionHelper::getPermissionNameMiddleware('delete', 'role'));
});
