<?php

namespace Modules\User\Services;

use App\Models\User;
use App\Services\FileOperationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Role\Services\RoleService;

class UserService
{
    public User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        return $this->userModel::with(['avatar', 'roles'])
            ->whereIsEmployee()
            ->paginatedCollection();
    }

    public function store($data)
    {
        $errors = [];
        $role = (new RoleService())->validRoleExists($data['role_id'], $errors);

        if ($errors) {
            return $errors;
        }

        $user = $this->userModel::create($data + ['type' => UserTypeEnum::ADMIN_EMPLOYEE, 'status' => true]);

        $user->assignRole($role);

        (new FileOperationService())->storeImageFromRequest($user, AuthEnum::AVATAR_COLLECTION_NAME, 'avatar');

        return true;
    }

    public function show($id): Model|Builder|User
    {
        return $this->userModel::whereId($id)->whereIsEmployee()->with(['avatar', 'roles'])->firstOrFail();
    }

    public function update($data, $id)
    {
        $errors = [];
        $user = $this->userModel::whereId($id)->whereIsEmployee()->firstOrFail();

        $role = (new RoleService())->validRoleExists($data['role_id'], $errors);

        if ($errors) {
            return $errors;
        }

        $user->update($data);

        $user->syncRoles($role);

        if (isset($data['avatar'])) {
            $user->getRegisteredMediaCollections();
            (new FileOperationService())->storeImageFromRequest($user, AuthEnum::AVATAR_COLLECTION_NAME, 'avatar');
        }

        return true;
    }

    public function destroy($id): bool
    {
        $user = $this->userModel::whereId($id)->whereIsEmployee()->firstOrFail();

        $user->delete();

        return true;
    }

    public function changeStatus($id): bool
    {
        $user = $this->userModel::where(['id' => $id], ['id', '!=', Auth::id()])->firstOrFail();

        $user->update(['status' => (! $user->status)]);

        if ($user->status == 0) {
            $user->tokens()->delete();

            return false;
        }

        return true;
    }
}
