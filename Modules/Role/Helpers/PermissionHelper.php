<?php

namespace Modules\Role\Helpers;

use App\Models\User;
use Modules\Auth\Enums\UserTypeEnum;

class PermissionHelper
{
    public static function generatePermissionName(string $operation, string $permission)
    {
        return "$operation-$permission";
    }

    public static function getPermissionNameMiddleware(string $operation, string $permission)
    {
        return 'permission:'.static::generatePermissionName($operation, $permission);
    }

    public static function setPermissionsTeamID(int $teamID): void
    {
        setPermissionsTeamId($teamID);
    }

    public static function getPermissionsTeamID(): int|string|null
    {
        return getPermissionsTeamId();
    }

    public static function getRoleIndex(string $roleName)
    {
        return array_search($roleName, self::availableRolesIndices());
    }

    public static function availableRolesIndices(): array
    {
        return [
            UserTypeEnum::ADMIN,
        ];
    }

    public static function roleModel()
    {
        return config('permission.models.role');
    }

    public static function permissionModel()
    {
        return config('permission.models.permission');
    }

    public static function canAccessPermissionKey(User $user = null)
    {
        $user = $user ?: auth()->user();

        return 'can_access->['.PermissionHelper::getRoleIndex(UserTypeEnum::getUserType($user)).']';
    }

    public static function getCachedPermissions()
    {
        if (! cache()->has('permissions')) {
            self::setCachedPermissions();
        }

        return cache()->get('permissions');
    }

    public static function setCachedPermissions(): void
    {
        cache()->set('permissions', PermissionHelper::permissionModel()::all(['id', 'name']));
    }
}
