<?php

namespace Modules\Auth\Http\Middleware;

use App\Traits\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Modules\Auth\Enums\UserTypeEnum;

class PassCodeMiddleware
{
    use HttpResponse;

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && UserTypeEnum::getCurrentUserAlphaType() == UserTypeEnum::USER) {
            //           $passCodeToken = $request->header('Pass-Code-Token');
            //
            //           $tokenExists = auth()->user()->passCodes()
            //               ->where('token', hash('sha256', $passCodeToken))
            //               ->exists();
            //
            //           if(! $tokenExists)
            //           {
            //               return $this->forbiddenResponse(data: ['invalid_pass_code' => true]);
            //           }
        }

        return $next($request);
    }
}
