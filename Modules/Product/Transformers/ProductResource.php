<?php

namespace Modules\Product\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserResource;
use Modules\Category\Transformers\CategoryResource;
use Modules\Markable\Helpers\FavoriteHelper;
use Modules\Product\Http\Controllers\ClientProductController;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'minimum_days' => $this->whenHas('minimum_days'),
            'maximum_days' => $this->whenHas('maximum_days'),
            'health' => $this->whenHas('health'),
            'category_id' => $this->whenHas('category_id'),
            'description' => $this->whenHas('description'),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'price' => $this->whenHas('price'),
            'main_image' => $this->whenNotNull(ResourceHelper::getFirstMediaOriginalUrl($this, 'main_image')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'favorite' => $this->whenNotNull(FavoriteHelper::resourceFavorite($this)),
            'other_images' => $this->whenNotNull(ResourceHelper::getImagesObject($this, 'other_images')),
        ];
    }
}
