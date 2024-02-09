<?php

namespace Modules\Role\Http\Middleware;

use App\Traits\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Modules\Role\Helpers\PermissionHelper;

class SetPermissionTeamIdMiddleware
{
    use HttpResponse;

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            PermissionHelper::setPermissionsTeamID(auth()->user()->team_id);
        }

        return $next($request);
    }
}
