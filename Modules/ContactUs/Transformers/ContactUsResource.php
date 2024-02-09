<?php

namespace Modules\ContactUs\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->whenHas('phone'),
            'status' => $this->whenHas('status', (bool) $this->status),
            'message' => $this->whenHas('message'),
        ];
    }
}
