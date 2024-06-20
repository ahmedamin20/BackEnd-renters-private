<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Services\LoginService;
use Modules\Auth\Transformers\UserResource;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly LoginService $loginService)
    {
    }

    public function loginMobile(LoginRequest $request): JsonResponse
    {
        $result = $this->loginService->loginMobile($request->validated());

        return $this->loginUser($result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function loginSpa(LoginRequest $request): JsonResponse
    {
        $result = $this->loginService->loginSpa($request->validated());

        return $this->loginUser($result);
    }

    protected function loginUser($result): JsonResponse
    {
        if ($result instanceof User) {
            return $this->okResponse(
                new UserResource($result),
                translate_success_message('user', 'logged')
            );

        } elseif (isset($result['not_verified']) || isset($result['frozen']) || isset($result['identity_not_verified'])) {
            $message = translate_error_message(
                'user',
                isset($result['not_verified'])
                    ? 'not_verified'
                    : 'frozen'
            );

            if(isset($data['identity_not_verified'])) {
                $message = 'Your identity verification is pending, we will notify you on email once it\'s done';
            }

            return $this->errorResponse(
                null,
                Response::HTTP_FORBIDDEN,
                $message,
                additional: ['verified' => ! isset($result['not_verified'])]
            );
        }

        return $this->errorResponse(
            null,
            Response::HTTP_UNAUTHORIZED,
            translate_word('wrong_credentials'),
        );
    }
}
