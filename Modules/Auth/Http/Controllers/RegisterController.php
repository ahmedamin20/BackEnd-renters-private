<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Services\RegisterService;
use Modules\Auth\Services\VerifyEmailService;

class RegisterController extends Controller
{
    use HttpResponse;

    protected RegisterService $registerService;

    public function __construct(RegisterService $registerService, private readonly VerifyEmailService $verifyUser)
    {
        $this->registerService = $registerService;
    }

    public function handle(RegisterRequest $request): JsonResponse
    {
        $result = $this->registerService->registerNewUser($request->validated());

        if (is_bool($result)) {
            return $this->createdResponse(
                message: translate_success_message('user', 'created')
                .' '.translate_word('user_verification_sent')
            );
        }

        return $this->validationErrorsResponse($result);
    }
}
