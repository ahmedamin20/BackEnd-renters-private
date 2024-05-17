<?php

namespace Modules\Product\Services;

use App\Exceptions\ValidationErrorsException;
use App\Helpers\UserHelper;
use App\Http\Controllers\SearchController;
use App\Services\FileOperationService;
use Modules\Category\Entities\Category;
use Modules\Category\Services\AdminCategoryService;
use Modules\Feature\Entities\Feature;
use Modules\Feature\Entities\FeatureRelation;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Controllers\ClientProductController;

class ProductService
{
    public function index()
    {
        return Product::latest()
            ->whereUserId(auth()->id())
            ->searchable(['name'])
            ->searchByForeignKey('category_id', request()->input('category_id'))
            ->with(['main_image', 'category:id,name', 'user' => function($query){
                $query->select(['id', 'name']);
            }])
            ->paginatedCollection();
    }

    public function show(int $id)
    {
        return Product::with(['main_image', 'other_images', 'category'])
            ->whereId($id)
            ->whereUserId(auth()->id())
            ->firstOrFail();
    }

    /**
     * @return bool|array
     */
    public function store(array $data)
    {
        return $this->storeOrUpdate($data);
    }

    protected function storeOrUpdate(array $data, $productID = null): bool|array
    {
        $fileOperationService = new FileOperationService();
        $inUpdate = ! is_null($productID);
        $errors = [];
        $loggedUserID = auth()->id();

        if ($inUpdate) {
            $product = Product::whereId($productID)
                ->whereUserId($loggedUserID)
                ->firstOrFail();
        }


        (new AdminCategoryService())->categoryExists($data['category_id']);


        if ($errors) {
            return $errors;
        }

        if (! $inUpdate) {
            $product = Product::create($data + ['user_id' => $loggedUserID]);

            //TODO Store The Main Image
            $fileOperationService->storeImageFromRequest(
                $product,
                ClientProductController::$collectionName,
                'main_image',
            );

            //TODO Store Other Product Images
            if(isset($data['other_images']))
            {
                foreach($data['other_images'] as $media)
                {
                    $fileOperationService->addMedia($product, $media, 'other_images');
                }
            }
        } else {
            if (isset($data['main_image'])) {
                $product->getFirstMedia('main_image')?->delete();
                //TODO Store The Main Image
                $fileOperationService->storeImageFromRequest(
                    $product,
                    ClientProductController::$collectionName,
                    'main_image',
                );
            }

            if(isset($data['other_images']))
            {
                foreach($data['other_images'] as $media)
                {
                    $fileOperationService->addMedia($product, $media, 'other_images');
                }
            }

            if(isset($data['deleted_images']))
            {
                $product
                    ->other_images()
                    ->whereIntegerInRaw('id', $data['deleted_images'])
                    ->get()
                    ->map(fn($item) => $item->delete());
            }

            $product->update($data);
        }

        return true;
    }

    public function update(array $data, int $id): bool|array
    {
        return $this->storeOrUpdate($data, $id);
    }

    /**
     * @throws ValidationErrorsException
     */
    public function productExists($productId)
    {
        $product = Product::whereId($productId)->first();

        if(! $product)
        {
            throw new ValidationErrorsException(['product_id' => 'Product not found!']);
        }

        return $product;
    }
}
