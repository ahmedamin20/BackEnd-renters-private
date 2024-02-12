<?php

namespace Modules\Category\Services;

use App\Services\FileOperationService;
use Illuminate\Support\Str;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Controllers\AdminCategoryController;

class AdminCategoryService extends BaseCategoryService
{
    protected Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function index()
    {
        return $this->baseIndex();
    }

    public function store(array $data): bool
    {
        return $this->createOrUpdate($data);
    }

    public function show($id)
    {
        return $this->baseShow($id);
    }

    public function update(array $data, $id): bool
    {
        return $this->createOrUpdate($data, $id);
    }

    protected function createOrUpdate(array $data, $id = null): bool
    {
        $inUpdate = (bool) $id;

        if (! $inUpdate) {
            $category = $this->categoryModel::create($data);
        } else {
            $category = Category::whereId($id)->firstOrFail();
            $category->update($data);
        }

        if (isset($data['image'])) {
            if ($inUpdate) {
                $category->getFirstMedia(AdminCategoryController::$collectionName)?->delete();
            }
            (new FileOperationService())->storeImageFromRequest(
                $category,
                AdminCategoryController::$collectionName,
                'image',
                Str::random().'.svg'
            );
        }

        return true;
    }
}
