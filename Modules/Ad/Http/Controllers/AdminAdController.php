<?php

namespace Modules\Ad\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Ad\Entities\Ad;
use Modules\Ad\Http\Requests\AdRequest;
use Modules\Ad\Services\AdminAdService;
use Modules\Ad\Transformers\AdResource;

class AdminAdController extends Controller
{
    use HttpResponse;

    public static string $collectionName = 'ads';

    public function __construct(private readonly AdminAdService $adService)
    {
    }

    public function index(): JsonResponse
    {
        $ads = $this->adService->index();

        return $this->paginatedResponse(
            $ads,
            AdResource::class,
        );
    }

    public function show(Ad $ad): JsonResponse
    {
        $ad->load('image');

        return $this->resourceResponse(
            new AdResource($ad, false),
        );
    }

    public function store(AdRequest $request): JsonResponse
    {
        $result = $this->adService->store($request->validated());

        if (is_bool($result)) {
            return $this->createdResponse(
                message: translate_success_message('ad', 'created')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function update(AdRequest $request, Ad $ad): JsonResponse
    {
        $result = $this->adService->update($request->validated(), $ad);

        if (is_bool($result)) {
            return $this->okResponse(
                message: translate_success_message('ad', 'updated')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function destroy(Ad $ad): JsonResponse
    {
        $ad->delete();

        return $this->okResponse(
            message: translate_success_message('ad', 'deleted')
        );
    }
}
