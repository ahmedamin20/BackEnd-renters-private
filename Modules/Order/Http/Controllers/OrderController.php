<?php

namespace Modules\Order\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Services\OrderService;
use Modules\Order\Transformers\OrderResource;

class OrderController extends Controller
{
    use HttpResponse;

    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = $this->orderService->index();

        return $this->paginatedResponse($orders, OrderResource::class);
    }

    public function show($id)
    {
        $order = $this->orderService->show($id);

        return $this->resourceResponse(OrderResource::make($order));
    }

    public function store(OrderRequest $request)
    {
        $this->orderService->store($request->validated());

        return $this->createdResponse(message: 'Order Created Successfully');
    }

    public function cancel($id)
    {
        Order::whereId($id)
            ->where('from_user_id', auth()->id())
            ->where('status', OrderStatusEnum::PENDING)
            ->firstOrFail()
            ->update(['status' => OrderStatusEnum::CANCELED]);

        return $this->okResponse(message: 'Order Canceled Successfully');
    }

    public function accept($id)
    {
        DB::transaction(function() use ($id){
            $order = Order::whereId($id)
                ->where('to_user_id', auth()->id())
                ->where('status', OrderStatusEnum::PENDING)
                ->firstOrFail();

            $product = $order->products->first();
            $product->forceFill(['renting_now' => true])->save();
            $order->update(['status' => OrderStatusEnum::RENTING]);
        });

        return $this->okResponse(message: 'Order Accepted Successfully');
    }

    public function reject($id)
    {
        Order::whereId($id)
            ->where('to_user_id', auth()->id())
            ->where('status', OrderStatusEnum::PENDING)
            ->firstOrFail()
            ->update(['status' => OrderStatusEnum::REJECTED]);

        return $this->okResponse(message: 'Order Rejected Successfully');
    }
}
