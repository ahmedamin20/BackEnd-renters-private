<?php

namespace Modules\Role\Database\Seeders;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Role\Helpers\PermissionHelper;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run(): void
    {
        Model::unguard();

        $this->resetCachedRoles();

        $this->generateAllowedRolesPermissions();

    }

    protected function resetCachedRoles()
    {
        Artisan::call('permission:cache-reset');
    }

    private function allPermissions()
    {

        $allPermissions = [
            'ad' => $this->excludeOperations(),
            'blog' => $this->excludeOperations(),
            'contact_us' => $this->excludeOperations(['show', 'store', 'update']),
            'about_us' => $this->excludeOperations(['show', 'store', 'update']),
            'expense_type' => $this->excludeOperations(),
            'expense' => $this->excludeOperations(),
            'section' => $this->excludeOperations(),
            'service' => $this->excludeOperations(),
            'settings' => $this->excludeOperations(['all', 'store', 'delete']),
            'terms_and_conditions' => $this->excludeOperations(['all', 'store', 'delete']),
            'role' => $this->excludeOperations(),
        ];

        $latestPermissions = [];

        foreach ($allPermissions as $parentPermission => $operations) {
            $permission = [];
            foreach ($operations as $operation) {
                $permission['name'] = PermissionHelper::generatePermissionName($operation, $parentPermission);
                $permission['guard_name'] = 'web';
                $latestPermissions[] = $permission;
            }
        }

        PermissionHelper::permissionModel()::insert($latestPermissions);

        PermissionHelper::setCachedPermissions();

        return collect($latestPermissions)->pluck('name')->toArray();

    }

    private function excludeOperations(array $excludedOperations = [], bool $excludeAll = false, array $additionalPermissions = []): array
    {
        if ($excludeAll) {
            return [];
        }

        $availableOperations = array_merge(['all', 'show', 'store', 'update', 'delete'], $additionalPermissions);

        return array_diff($availableOperations, $excludedOperations);
    }

    private function generateAllowedRolesPermissions()
    {
        $allPermissions = PermissionHelper::permissionModel()::whereIn('name', $this->allPermissions())->get();
        $rolesWithAllowedPermissions = [
            UserTypeEnum::ADMIN => [
                'ad' => $this->excludeOperations(),
                'blog' => $this->excludeOperations(),
                'contact_us' => $this->excludeOperations(['show', 'store', 'update']),
                'about_us' => $this->excludeOperations(['all', 'store', 'delete']),
                'expense_type' => $this->excludeOperations(),
                'expense' => $this->excludeOperations(),
                'section' => $this->excludeOperations(),
                'service' => $this->excludeOperations(),
                'settings' => $this->excludeOperations(['all', 'store', 'delete']),
                'terms_and_conditions' => $this->excludeOperations(['all', 'store', 'delete']),
                'role' => $this->excludeOperations(),
            ],
            UserTypeEnum::ADMIN_EMPLOYEE => [
                'ad' => $this->excludeOperations(),
                'blog' => $this->excludeOperations(),
            ],
        ];

        foreach ($rolesWithAllowedPermissions as $roleName => $permissions) {
            $createdRole = PermissionHelper::roleModel()::create([
                'name' => $roleName,
            ]);

            $excludedPermissions = [];
            foreach ($permissions as $permissionName => $operations) {
                foreach ($operations as $operation) {
                    $excludedPermissions[] = PermissionHelper::generatePermissionName($operation, $permissionName);
                }
            }

            $allowedRolePermissions = $allPermissions->filter(function ($element) use ($excludedPermissions) {
                return in_array($element->name, $excludedPermissions);
            })->pluck('id')->toArray();

            $createdRole->syncPermissions($allowedRolePermissions);
        }
    }
}
