<?php

namespace Modules\User\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Role\Transformers\RoleResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->whenHas('id'),
            'name' => $this->whenHas('name'),
            'email' => $this->whenHas('email'),
            $this->mergeWhen($this->relationLoaded('roles'), function () {
                return [
                    'role' => new RoleResource($this->roles->first()),
                ];
            }),
            'avatar' => ResourceHelper::getFirstMediaOriginalUrl($this),
        ];
    }
}
