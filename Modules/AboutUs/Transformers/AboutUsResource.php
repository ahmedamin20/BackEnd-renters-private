<?php

namespace Modules\AboutUs\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutUsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->whenHas('name'),
            'description' => $this->whenHas('description'),
            'youtube_video_url' => $this->whenHas('youtube_video_url'),
            'image' => $this->whenNotNull(ResourceHelper::getFirstMediaOriginalUrl($this, 'image')),
        ];
    }
}
