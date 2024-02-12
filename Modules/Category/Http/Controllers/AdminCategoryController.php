<?php

namespace Modules\Category\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Services\AdminCategoryService;
use Modules\Category\Transformers\CategoryResource;

class AdminCategoryController extends Controller
{
    use HttpResponse;

    public static string $collectionName = 'category';

    public function __construct(private readonly AdminCategoryService $adminCategoryService)
    {
    }

    public function index(): JsonResponse
    {
        $category = $this->adminCategoryService->index();

        return $this->paginatedResponse(
            $category,
            CategoryResource::class,
        );
    }

    public function show($id): JsonResponse
    {
        $category = $this->adminCategoryService->show($id);

        return $this->resourceResponse(
            CategoryResource::make($category)
        );
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $this->adminCategoryService->store($request->validated());

        return $this->createdResponse(
            message: translate_success_message('category', 'created')
        );
    }

    public function update(CategoryRequest $request, $id): JsonResponse
    {
        $this->adminCategoryService->update($request->validated(), $id);

        return $this->okResponse(
            message: translate_success_message('category', 'updated')
        );

    }

    public function destroy($id): JsonResponse
    {
        $category = Category::whereId($id)->firstOrFail();
        $category->delete();

        return $this->okResponse(
            message: translate_success_message('category', 'deleted')
        );
    }
}
