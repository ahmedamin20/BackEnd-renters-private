<?php

namespace Modules\Category\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->whenHas('name'),
            'image' => $this->whenNotNull(ResourceHelper::getFirstMediaOriginalUrl($this, 'image')),
        ];
    }
}
