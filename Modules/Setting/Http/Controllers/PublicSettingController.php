<?php

namespace Modules\Setting\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Transformers\SettingResource;

class PublicSettingController extends Controller
{
    use HttpResponse;

    public function show(): JsonResponse
    {
        $settings = Setting::first();

        return $this->resourceResponse(
            SettingResource::make($settings)
        );
    }
}
