<?php

namespace Modules\Role\Transformers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class RoleResource extends JsonResource
{
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
