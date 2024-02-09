<?php

namespace Modules\Setting\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    protected array $fullyTranslatedContent;

    public function __construct($resource, array $fullyTranslatedContent = [])
    {
        parent::__construct($resource);

        $this->fullyTranslatedContent = $fullyTranslatedContent;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'whatsapp' => $this->whatsapp,
            'youtube' => $this->youtube,
            'working_hours' => $this->working_hours,
        ];
    }
}
