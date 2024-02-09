<?php

namespace Modules\Role\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Role\Classes\RoleConfigClass;

/**
 * <b>Config Facade Class
 *
 * @method static string roleConfigFileName() Get Role Module Config File Name
 * @method static array permissions() Get all permissions
 * @method static array getRolesWithExcludedPermissions() Get all roles with excluded permissions
 * @method static array getRoles() Get all roles names
 * @method static string getSpatieMiddleware(string $middlewareName) Get Spatie Middleware
 * @method static bool registerSpatieMiddlewares() Determine if registering spatie middlewares
 * @method static mixed getMiddleware() Get Middleware
 * @method static array getExcludedPermissionsForRole(string $roleName) Get excluded permissions for a given role
 *
 * @see RoleConfigClass
 */
class Role extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RoleConfigClass::class;
    }
}
