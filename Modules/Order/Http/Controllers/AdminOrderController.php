<?php

namespace Modules\Order\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Transformers\OrderResource;

class AdminOrderController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $status = request()->input('status', OrderStatusEnum::RENTING);

        $orders = Order::latest()
            ->when(! is_null($status), fn($builder) => $builder->where('status', $status))
            ->with([
                'fromUser',
                'toUser',
                'products' => function($query){
                    $query->select(['products.id', 'name', 'rating_average', 'health', 'description', 'category_id']);
                    $query->with('main_image', 'category:id,name');
                }
            ])
            ->paginatedCollection();

        return $this->paginatedResponse($orders, OrderResource::class);
    }

    public function show($order)
    {
        $order = Order::with([
            'fromUser',
            'toUser',
            'products' => function($query){
                $query->select(['products.id', 'name', 'rating_average', 'health', 'description', 'category_id']);
                $query->with('main_image', 'category:id,name');
            }
        ])->findOrFail($order);

        return $this->resourceResponse(OrderResource::make($order));
    }
}
