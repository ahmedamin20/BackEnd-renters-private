<?php

namespace Modules\Ad\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Ad\Services\PublicAdService;
use Modules\Ad\Transformers\AdResource;

class PublicAdController extends Controller
{
    use HttpResponse;

    private PublicAdService $adService;

    public function __construct()
    {
        $this->adService = new PublicAdService();
    }

    public function index(): JsonResponse
    {
        return $this->resourceResponse(
            AdResource::collection($this->adService->index())
        );
    }
}
