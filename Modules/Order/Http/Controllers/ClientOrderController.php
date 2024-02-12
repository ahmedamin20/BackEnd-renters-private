<?php

namespace Modules\Order\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Notification\Helpers\NotificationHelper;
use Modules\Order\Entities\Order;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Http\Requests\ClientMakeOrderRequest;
use Modules\Order\Services\ClientOrderService;
use Modules\Order\Transformers\OrderResource;
use Modules\Product\Entities\Product;

class ClientOrderController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly ClientOrderService $clientOrderService)
    {
    }

    public function index()
    {
        $orders = $this->clientOrderService->index();

        return $this->resourceResponse(
            OrderResource::collection($orders)
        );
    }

    public function show(int $order)
    {
        $order = $this->clientOrderService->show($order);

        //        return $order;
        return $this->resourceResponse(
            new OrderResource($order)
        );
    }

    public function store(ClientMakeOrderRequest $request)
    {
        $result = $this->clientOrderService->store($request->validated());

        if ($result instanceof Order) {
            $notifiable = User::whereId(
                Product::whereId($result->product_id)
                    ->value('user_id')
            )
                ->firstOrFail(['id']);

            NotificationHelper::notifyUser(
                $notifiable,
                'order_created',
                [
                    'id' => $result->id,
                    'type' => 'client_order',
                ]
            );

            return $this->createdResponse(
                message: translate_success_message('order', 'created')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function destroy(int $order): JsonResponse
    {
        $order = Order::whereId($order)
            ->whereStatus(OrderHelper::pendingStatus())
            ->whereUserId(auth()->id())
            ->firstOrFail(['id']);

        $order->features()->delete();
        $order->delete();

        return $this->okResponse(
            message: translate_success_message('order', 'deleted')
        );
    }
}
