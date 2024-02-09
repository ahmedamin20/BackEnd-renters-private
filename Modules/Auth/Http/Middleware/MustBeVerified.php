<?php

namespace Modules\Auth\Http\Middleware;

use App\Traits\HttpResponse;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Auth\Contracts\VerifyUser;
use Symfony\Component\HttpFoundation\Response;

class MustBeVerified
{
    use HttpResponse;

    public function __construct(private readonly VerifyUser $verifyUser)
    {

    }

    /**
     * @return JsonResponse|mixed
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! auth()->check()) {
            $this->throwNotAuthenticated();
        }

        if (! $this->verifyUser->isUserVerified($request->user())) {
            return $this->errorResponse(
                null,
                Response::HTTP_FORBIDDEN,
                translate_error_message('user', 'not_verified'),
                ['verified' => false]
            );
        }

        return $next($request);
    }
}
