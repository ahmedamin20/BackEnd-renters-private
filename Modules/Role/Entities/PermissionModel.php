<?php

namespace Modules\Role\Entities;

use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission as Model;

/**
 * Modules\Role\Entities\PermissionModel
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, PermissionModel> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, RoleModel> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static Builder|PermissionModel newModelQuery()
 * @method static Builder|PermissionModel newQuery()
 * @method static Builder|Permission permission($permissions)
 * @method static Builder|PermissionModel query()
 * @method static Builder|Permission role($roles, $guard = null)
 * @method static Builder|PermissionModel whereCorrectPermissions(?int $userType = null)
 * @method static Builder|PermissionModel whereCreatedAt($value)
 * @method static Builder|PermissionModel whereGuardName($value)
 * @method static Builder|PermissionModel whereId($value)
 * @method static Builder|PermissionModel whereName($value)
 * @method static Builder|PermissionModel whereUpdatedAt($value)
 *
 * @property array $can_access accessible by admin, seller, customer in the same order
 *
 * @method static Builder|PermissionModel whereCanAccess($value)
 * @method static Builder|PermissionModel whereAuthorizedPermissions()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutRole($roles, $guard = null)
 *
 * @mixin Eloquent
 */
class PermissionModel extends Model
{
    protected $fillable = [
        'name',
        'guard_name',
        'can_access',
    ];

    protected $casts = [
        'can_access' => 'array',
    ];
}
