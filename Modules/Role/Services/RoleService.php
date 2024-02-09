<?php

namespace Modules\Role\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Role\Entities\RoleModel;
use Modules\Role\Helpers\PermissionHelper;
use Spatie\Permission\PermissionRegistrar;

class RoleService
{
    private $roleModel;

    private $permissionModel;

    public function __construct()
    {
        $this->roleModel = PermissionHelper::roleModel();
        $this->permissionModel = PermissionHelper::permissionModel();
    }

    public function store(array $data): bool|array
    {
        return $this->storeOrUpdate($data);
    }

    public function update(array $data, int $id)
    {
        return $this->storeOrUpdate($data, $id);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function storeOrUpdate(array $data, int $roleID = null): bool|array
    {
        $errors = [];
        $inUpdate = ! is_null($roleID);

        if ($inUpdate) {
            $role = $this->roleModel::whereValid()
                ->whereId($roleID)
                ->firstOrFail();
        }

        // Checking if a role with requested name is exists

        if ($this->roleExists($data['name'], $inUpdate, $roleID, $errors)) {
            $errors['name'] = translate_error_message('name', 'exists');

            return $errors;
        }

        $requestPermissions = $data['permissions'];

        $this->checkIfAllPermissionsExists($requestPermissions, $errors);

        if ($errors) {
            return $errors;
        }

        if (! $inUpdate) {
            $role = $this->roleModel::create($data + ['guard_name' => 'web']);

        } else {
            $role->update($data);
            $role->permissions()->detach();
        }

        $role->permissions()->attach($requestPermissions);

        // Reset cached roles and permissions
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        return true;
    }

    private function indexNotExistingPermissions(array $requestPermissions, array $existingPermissions, array &$errors): void
    {
        // list all not found permissions for the client
        $index = 0;

        foreach ($requestPermissions as $permission) {
            if (! in_array($permission, $existingPermissions)) {
                $errors["permissions.$index"] = translate_error_message('permission', 'not_exists');
            }

            $index++;
        }
    }

    public function roleExists(string $roleName, bool $inUpdate, int $roleID = null, array $errors = [])
    {
        return $this->roleModel::whereName($roleName)
            ->where(function ($query) use ($roleID, $inUpdate) {
                if ($inUpdate) {
                    $query->where('id', '<>', $roleID);
                }
            })
            ->select(['id'])
            ->exists();
    }

    private function checkIfAllPermissionsExists(array $requestPermissions, array &$errors): void
    {
        //TODO Checking if all permissions exists

        $permissions = PermissionHelper::getCachedPermissions()->whereIn('id', $requestPermissions);

        if (count($requestPermissions) != $permissions->count()) {
            $this->indexNotExistingPermissions(
                $requestPermissions,
                $permissions->pluck('id')->toArray(),
                $errors
            );
        }
    }

    public function validRoleExists($roleId, array &$errors, string $errorKey = 'role_id')
    {
        $role = RoleModel::whereValid()->whereId($roleId)->first();

        if (! $role) {
            $errors[$errorKey] = translate_error_message('role', 'not_exists');
        }

        return $errors ?: $role;
    }
}
