<?php

namespace Modules\Category\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Category\Services\PublicCategoryService;
use Modules\Category\Transformers\CategoryResource;

class PublicCategoryController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly PublicCategoryService $publicCategoryService)
    {
    }

    public function index(): JsonResponse
    {
        $categories = $this->publicCategoryService->index();

        return $this->paginatedResponse($categories, CategoryResource::class);
    }

    public function show($id): JsonResponse
    {
        $category = $this->publicCategoryService->show($id);

        return $this->resourceResponse(
            CategoryResource::make($category)
        );
    }
}
