<?php

namespace Modules\Category\Services;

use App\Exceptions\ValidationErrorsException;
use Modules\Category\Entities\Category;

class BaseCategoryService
{
    public function baseIndex()
    {
        return Category::latest()
            ->with('image')
            ->searchable(['name'])
            ->paginatedCollection();
    }

    protected function baseShow($id)
    {
        return Category::with('image')->whereId($id)->firstOrFail();
    }

    /**
     * @throws ValidationErrorsException
     */
    public function categoryExists($categoryId, $errorKey = 'category_id')
    {
        $category = Category::whereId($categoryId)->first();
        $errors = [];

        if (! $category) {
            $errors[$errorKey] = translate_error_message('category', 'not_exists');

            throw new ValidationErrorsException($errors);
        }

        return $category;
    }
}
