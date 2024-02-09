<?php

namespace Modules\Role\Entities;

use App\Models\User;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Modules\Auth\Enums\UserTypeEnum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as Model;

/**
 * Modules\Role\Entities\RoleModel
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel formatResult()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel paginatedCollection()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel whereValid(array $customTypes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel searchable(array $columns = [], string $handleKeyName = 'handle')
 * @method static \Illuminate\Database\Eloquent\Builder|RoleModel searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role withoutPermission($permissions)
 *
 * @mixin \Eloquent
 */
class RoleModel extends Model
{
    use PaginationTrait, Searchable;

    public function scopeWhereValid(Builder $query, array $customTypes = [])
    {
        $forbiddenRoles = $customTypes ?: [
            UserTypeEnum::ADMIN,
        ];

        return $query->whereNotIn('name', $forbiddenRoles);
    }
}
