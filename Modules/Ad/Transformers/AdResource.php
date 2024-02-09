<?php

namespace Modules\Ad\Transformers;

use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    private bool $showDetails = true;

    public function __construct($resource, bool|int $showDetails = true)
    {
        parent::__construct($resource);

        if (is_bool($showDetails)) {
            $this->showDetails = $showDetails;
        }
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->whenHas('description'),
            'discount' => $this->discount.($this->showDetails ? '%' : ''),
            'image' => $this->whenNotNull(ResourceHelper::getFirstMediaOriginalUrl($this, 'image')),
        ];
    }
}
