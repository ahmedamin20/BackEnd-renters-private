<?php

namespace Modules\Category\Services;

use Modules\Category\Entities\Category;

class PublicCategoryService extends BaseCategoryService
{
    public function index()
    {
        return Category::latest()
            ->with('image')
            ->latest()
            ->paginatedCollection();
    }

    public function show($id)
    {
        return Category::with('image')
            ->whereId($id)
            ->firstOrFail();
    }
}
