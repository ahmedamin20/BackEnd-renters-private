<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Modules\Auth\Actions\ChangePassword;
use Modules\Auth\Actions\ForgotPassword;
use Modules\Auth\Actions\ResetPassword;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;

class PasswordController extends Controller
{
    use HttpResponse;

    public function changePassword(
        ChangePasswordRequest $request,
        ChangePassword $changePassword
    ): JsonResponse {
        $changePassword->handle($request->validated(), auth()->id());

        return $this->okResponse(
            message: translate_success_message('password', 'changed')
        );
    }

    public function forgotPassword(
        ForgotPasswordRequest $request,
        ForgotPassword $forgotPassword
    ): JsonResponse {
        $result = $forgotPassword->handle($request->validated());

        if ($result == Password::RESET_LINK_SENT) {
            return $this->okResponse(message: __($result));
        } elseif (! is_array($result)) {
            $result = ['email' => $result];
        }

        return $this->validationErrorsResponse($result);
    }

    public function resetPassword(
        ResetPasswordRequest $request,
        ResetPassword $resetPassword
    ): JsonResponse {
        $result = $resetPassword->handle($request->validated());

        if ($result == Password::PASSWORD_RESET) {
            return $this->okResponse(message: __($result));
        }

        return $this->validationErrorsResponse(['email' => __($result)]);
    }
}
