<?php

namespace Modules\Auth\Transformers;

use App\Helpers\DateHelper;
use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\PricePlan\Transformers\PricePlanResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'rolesIds' => $this->whenHas('rolesIds'),
            AuthEnum::UNIQUE_COLUMN => $this->whenHas(AuthEnum::UNIQUE_COLUMN),
            'avatar' => $this->whenNotNull(
                ResourceHelper::getFirstMediaOriginalUrl(
                    $this,
                    AuthEnum::AVATAR_RELATIONSHIP_NAME,
                    'user.png'
                )
            ),
            'type' => $this->whenHas(
                'type',
            ),
            'token' => $this->whenHas('token'),
            $this->mergeWhen($this->relationLoaded('roles'), function () {
                $role = $this->roles->first();
                $permissions = [];

                if ($role?->relationLoaded('permissions')) {
                    foreach ($role->permissions as $permission) {
                        $permissions[] = $permission->name;
                    }
                }

                return [
                    'permissions' => $this->when((bool) $permissions, $permissions),
                ];
            }),
        ];
    }
}
