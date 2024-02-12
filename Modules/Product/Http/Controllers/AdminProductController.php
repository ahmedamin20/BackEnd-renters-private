<?php

namespace Modules\Product\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Transformers\ProductResource;

class AdminProductController extends Controller
{
    use HttpResponse;

    public function index()
    {
        return $this->paginatedResponse(
            Product::query()
                ->latest()
                ->with(['main_image', 'other_images', 'category:id,name', 'user' => function($query){
                    $query->select(['id', 'name']);
                }])->paginatedCollection(),
            ProductResource::class,
        );
    }

    public function show($id)
    {
        return $this->resourceResponse(
            new ProductResource(
                Product::with(['main_image', 'other_images', 'category'])
                    ->whereId($id)
                    ->firstOrFail()
            )
        );
    }
}
