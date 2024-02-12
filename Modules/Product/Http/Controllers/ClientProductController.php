<?php

namespace Modules\Product\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Services\ProductService;
use Modules\Product\Transformers\ProductResource;

class ClientProductController extends Controller
{
    use HttpResponse;

    public static string $collectionName = 'products';

    public static string $mainImageStoredFileName = 'main_image.png';

    public function __construct(private readonly ProductService $productService)
    {
    }

    public function index()
    {
        $products = $this->productService->index();

        return $this->paginatedResponse(
            $products,
            ProductResource::class,
        );
    }

    public function show($product)
    {
        $product = $this->productService->show($product);

        return $this->resourceResponse(
            new ProductResource($product)
        );
    }

    public function store(ProductRequest $request)
    {
        $result = $this->productService->store($request->validated());

        if (is_bool($result)) {
            return $this->createdResponse(
                message: translate_success_message('product', 'created')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function update(ProductRequest $request, $product)
    {
        $result = $this->productService->update($request->validated(), $product);

        if (is_bool($result)) {
            return $this->okResponse(
                message: translate_success_message('product', 'updated')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function destroy($product)
    {
        $product = Product::whereUserId(auth()->id())
            ->whereId($product)
            ->firstOrFail();

        $product->delete();

        return $this->okResponse(
            message: translate_success_message('product', 'deleted')
        );
    }
}
