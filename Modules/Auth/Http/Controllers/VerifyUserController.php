<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\VerifyUserEmail;
use Modules\Auth\Http\Requests\ResendVerifyCodeRequest;
use Modules\Auth\Http\Requests\VerifyUserRequest;
use Modules\Auth\Services\VerifyEmailService;

class VerifyUserController extends Controller
{
    use HttpResponse;

    public static function getVerifyUserMiddleware(): array
    {
        $enableVerifyUser = true;

        return $enableVerifyUser ? ['must_be_verified'] : [];

    }

    public function verifyUser(VerifyUserRequest $request, VerifyEmailService $verifyUser)
    {
        $result = $verifyUser->verify(
            $request->input('handle'),
            $request->input('code')
        );

        if (is_bool($result)) {
            return $this->okResponse(
                message: translate_success_message('user', 'verified')
            );
        } elseif (isset($result['user_already_verified'])) {
            return $this->validationErrorsResponse(
                ['user_already_verified' => translate_word('user_already_verified')]
            );
        }

        return $this->notFoundResponse(
            $result['user_not_found'] ?? $result['user_verify_not_found']
        );
    }

    public function resendUserVerification(ResendVerifyCodeRequest $request, VerifyEmailService $verifyEmailService): JsonResponse
    {
        $handle = $request->handle;

        $result = $verifyEmailService->verifyUserInfo($handle);

        if (! isset($result['code'])) {
            return $this->validationErrorsResponse($result);
        }

        Mail::to($handle)->send(new VerifyUserEmail($result));

        return $this->okResponse(message: 'Email Verification Has Been Sent Successfully');
    }
}
