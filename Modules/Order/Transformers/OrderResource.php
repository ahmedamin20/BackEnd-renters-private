<?php

namespace Modules\Order\Transformers;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Modules\Auth\Transformers\UserResource;
use Modules\Product\Transformers\ProductResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'status' => $this->status,
            'from_date' => Carbon::parse($this->from_date)->format(DateHelper::defaultDateFormat()),
            'to_date' => Carbon::parse($this->to_date)->format(DateHelper::defaultDateFormat()),
            'created_at' => Carbon::parse($this->created_at)->format(DateHelper::amPmFormat()),
            'product' => $this->whenLoaded('products', function(){
                return ProductResource::make($this->products->first());
            }),
            'from_user' => UserResource::make($this->whenLoaded('fromUser')),
            'to_user' => UserResource::make($this->whenLoaded('toUser')),
        ];
    }
}
