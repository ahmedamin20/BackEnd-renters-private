<?php

namespace Modules\Role\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Role\Helpers\PermissionHelper;
use Modules\Role\Http\Requests\RoleRequest;
use Modules\Role\Services\RoleService;
use Modules\Role\Transformers\RoleResource;

class RoleController extends Controller
{
    use HttpResponse;

    private $roleModel;

    private $permissionModel;

    public function __construct()
    {
        $this->roleModel = PermissionHelper::roleModel();
        $this->permissionModel = PermissionHelper::permissionModel();
    }

    public function index()
    {
        $roles = $this->roleModel::whereValid()
            ->select(['id', 'name'])
            ->searchable()
            ->latest()
            ->paginatedCollection();

        return $this->paginatedResponse($roles, RoleResource::class);
    }

    public function show(int $role)
    {
        $role = $this->roleModel::whereValid()
            ->where('id', $role)
            ->with(['permissions' => fn ($query) => $query->select(['id', 'name'])])
            ->firstOrFail(['id', 'name']);

        $allPermissions = PermissionHelper::getCachedPermissions();

        $rolePermissions = $role->permissions->pluck('id')->toArray();
        for ($i = 0; $i < count($allPermissions); $i++) {
            $allPermissions[$i]->status = in_array($allPermissions[$i]->id, $rolePermissions);
        }

        $role->setRelation('permissions', $allPermissions);

        return $this->resourceResponse(new RoleResource($role));
    }

    public function store(RoleRequest $request, RoleService $roleService)
    {
        $result = $roleService->store($request->validated());

        if (is_bool($result) && $result) {
            return $this->createdResponse(message: translate_success_message('role', 'created'));
        } else {
            return $this->validationErrorsResponse($result);
        }
    }

    public function update(RoleRequest $request, RoleService $roleService, int $role)
    {
        $result = $roleService->update($request->validated(), $role);

        if (is_bool($result) && $result) {
            return $this->okResponse(message: translate_success_message('role', 'updated'));
        }

        return $this->validationErrorsResponse($result);
    }

    public function destroy(int $role): JsonResponse
    {
        $role = $this->roleModel::whereValid()
            ->where('id', $role)
            ->firstOrFail();

        $role->delete();

        return $this->okResponse(message: translate_success_message('role', 'deleted'));
    }
}
