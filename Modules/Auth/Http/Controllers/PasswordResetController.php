<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\PasswordResetRequest;
use Modules\Auth\Services\PasswordResetService;

class PasswordResetController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly PasswordResetService $passwordResetService)
    {
    }

    public function forgotPassword(PasswordResetRequest $request)
    {
        $result = $this->passwordResetService->forgotPassword($request->validated());

        if (is_bool($result)) {
            return $this->okResponse(
                message: translate_word('password_reset_sent')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $result = $this->passwordResetService->resetPassword($request->validated());

        if (is_bool($result)) {
            return $this->okResponse(
                message: translate_word('password_reset_successfully')
            );
        }

        return $this->validationErrorsResponse($result);
    }
}
