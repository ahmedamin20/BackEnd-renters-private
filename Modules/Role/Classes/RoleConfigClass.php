<?php

namespace Modules\Role\Classes;

class RoleConfigClass
{
    public function roleConfigFileName(): string
    {
        return 'permission';
    }

    public function permissions(): array
    {
        return (array) config($this->roleConfigFileName().'.permissions', []);
    }

    public function getRolesWithExcludedPermissions(): array
    {
        return (array) config($this->roleConfigFileName().'.roles', []);
    }

    public function getRoles(): array
    {
        return array_keys(
            (array) config($this->roleConfigFileName().'.roles', [])
        );
    }

    public function getExcludedPermissionsForRole(string $roleName): array
    {
        return (array) $this->getRolesWithExcludedPermissions()[$roleName] ?? [];
    }

    public function getSpatieMiddleware(string $middlewareName): string
    {
        return $this->getMiddleware("spatie.$middlewareName");
    }

    public function registerSpatieMiddlewares(): bool
    {
        return (bool) config($this->roleConfigFileName().'middleware.spatie.register_middlewares', false);
    }

    public function getMiddleware(string $middlewareName)
    {
        return config($this->roleConfigFileName().".middleware.$middlewareName");
    }
}
