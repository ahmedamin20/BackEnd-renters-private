<?php

use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Facades\AuthConfig;
use Modules\Auth\Facades\IsEnabled;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\LogoutController;
use Modules\Auth\Http\Controllers\PassCode\AdminPassCodeController;
use Modules\Auth\Http\Controllers\PassCode\UserPassCodeController;
use Modules\Auth\Http\Controllers\PasswordController;
use Modules\Auth\Http\Controllers\PasswordResetController;
use Modules\Auth\Http\Controllers\ProfileController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\RemoveAccountController;
use Modules\Auth\Http\Controllers\SocialAuthController;
use Modules\Auth\Http\Controllers\VerifyUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['guest']], function () {

    // Social Auth
    Route::post('social/callback', [SocialAuthController::class, 'handleProviderCallback']);

    // Logout
    if (IsEnabled::spaLogin() || IsEnabled::mobileLogin()) {
        // Logout
        Route::post('logout', LogoutController::class)
            ->name(AuthConfig::getRouteName('logout'))
            ->middleware(GeneralHelper::getDefaultLoggedUserMiddlewares());
    }

    Route::group(['prefix' => 'login'], function () {
        Route::post('dashboard', [LoginController::class, 'loginSpa']);
        Route::post('mobile', [LoginController::class, 'loginMobile']);
        Route::post('site', [LoginController::class, 'loginMobile']);
    });

    if (IsEnabled::register()) {
        // Register
        Route::post('register', [RegisterController::class, 'handle'])
            ->name(AuthConfig::getRouteName('register'));
    }

});

// Verify User
//if (IsEnabled::verifyUser()) {
Route::group(['prefix' => 'verify_user'], function () {

    Route::post('', [VerifyUserController::class, 'verifyUser'])
        ->name(AuthConfig::getRouteName('verify_user'))
        ->whereNumber('user')
        ->middleware('guest');

    Route::post('resend', [VerifyUserController::class, 'resendUserVerification'])
        ->middleware(['throttle:3,1', 'guest'])
        ->name(AuthConfig::getRouteName('resend_verify_user'));
});
//}

// Password

Route::group(['prefix' => 'password'], function () {

    // Change User Password
    Route::put('change_password', [PasswordController::class, 'changePassword'])
        ->middleware([GeneralHelper::authMiddleware()] + VerifyUserController::getVerifyUserMiddleware())
        ->name(AuthConfig::getRouteName('change_password'));

    if (IsEnabled::forgotPassword()) {
        Route::group(['prefix' => 'forgot_password'], function () {

            // Sent Reset Email For Resetting Password
            Route::post('', [PasswordResetController::class, 'forgotPassword'])
                ->middleware('guest')
                ->middleware(['throttle:3,1'])
                ->name('password.email');
        });

        Route::group(['prefix' => 'reset_password'], function () {
            // Process Resetting User Password
            Route::post('', [PasswordResetController::class, 'resetPassword'])
                ->middleware('guest')
                ->name('password.update');
        });
    }
});

Route::group(['middleware' => [GeneralHelper::authMiddleware()] + VerifyUserController::getVerifyUserMiddleware()], function () {

    // Profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('', [ProfileController::class, 'show']);
        Route::post('', [ProfileController::class, 'handle'])
            ->name(AuthConfig::getRouteName('profile'));
    });

    // Remove User Account
    Route::post('remove_account', RemoveAccountController::class);

    // Pass Code
    Route::group(['prefix' => 'admin/pass_code'], function () {
        Route::patch('', AdminPassCodeController::class);
    });
    Route::group(['prefix' => 'users/pass_code'], function () {
        Route::post('', [UserPassCodeController::class, 'store']);
        Route::post('validate', [UserPassCodeController::class, 'validate']);
        Route::patch('', [UserPassCodeController::class, 'update'])->middleware('pass_code');
    });
});
