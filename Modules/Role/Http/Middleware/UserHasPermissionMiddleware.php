<?php

namespace Modules\Role\Http\Middleware;

use Closure;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Role\Helpers\PermissionHelper;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserHasPermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $userHasPermission = auth()->user()
            ->permissions()
            ->where(
                'can_access->['.PermissionHelper::getRoleIndex(UserTypeEnum::getUserType()).']',
                1
            )
            ->whereIn('name', $permissions)
            ->exists();

        if ($userHasPermission) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
