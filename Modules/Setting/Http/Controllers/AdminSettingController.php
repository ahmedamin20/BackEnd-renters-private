<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Http\Requests\SettingRequest;
use Modules\Setting\Transformers\SettingResource;

class AdminSettingController extends Controller
{
    use HttpResponse;

    public function show(): JsonResponse
    {
        $settings = Setting::first();

        return $this->resourceResponse(
            SettingResource::make($settings)
        );
    }

    public function update(SettingRequest $request): JsonResponse
    {
        $settings = Setting::first();

        $settings->update($request->validated());

        return $this->okResponse(
            message: translate_success_message('settings', 'updated')
        );
    }
}
