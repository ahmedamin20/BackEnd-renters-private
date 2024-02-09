<?php

namespace Modules\AboutUs\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FileOperationService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Modules\AboutUs\Entities\AboutUs;
use Modules\AboutUs\Http\Requests\AboutUsRequest;
use Modules\AboutUs\Transformers\AboutUsResource;

class AdminAboutUsController extends Controller
{
    use HttpResponse;

    public static string $collectionName = 'about_us';

    /**
     * Display the specified resource.
     */
    public function show(): JsonResponse
    {
        $aboutUs = AboutUs::with('image')->first();

        return $this->resourceResponse(AboutUsResource::make($aboutUs));
    }

    public function update(AboutUsRequest $request, FileOperationService $fileOperationService): JsonResponse
    {
        $data = $request->validated();
        $aboutUs = AboutUs::find(1);
        $image = $aboutUs->getFirstMedia(static::$collectionName);

        if (isset($data['image'])) {
            $image?->delete();

            $fileOperationService->storeImageFromRequest(
                $aboutUs,
                static::$collectionName,
                'image'
            );
        }

        $aboutUs->update($data);

        return $this->okResponse(
            message: translate_success_message('about_us', 'updated')
        );
    }
}
