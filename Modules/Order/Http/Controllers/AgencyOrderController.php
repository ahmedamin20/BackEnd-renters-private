<?php

namespace Modules\Order\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Notification\Helpers\NotificationHelper;
use Modules\Order\Entities\Order;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Http\Requests\ApproveOrderRequest;
use Modules\Order\Services\AgencyOrderService;
use Modules\Order\Transformers\OrderResource;

class AgencyOrderController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly AgencyOrderService $agencyOrderService)
    {
    }

    public function index()
    {
        $allIncomingOrders = $this->agencyOrderService->index();

        return $this->resourceResponse(
            OrderResource::collection($allIncomingOrders)
        );
    }

    public function show(int $order)
    {
        $orderDetails = $this->agencyOrderService->show($order);

        return $this->resourceResponse(new OrderResource($orderDetails));
    }

    public function approveOrder(int $order, ApproveOrderRequest $request): JsonResponse
    {
        $result = $this->agencyOrderService->approveOrder(
            $request->validated() + ['order' => $order]
        );

        if ($result instanceof Order) {

            NotificationHelper::notifyUser(
                User::whereId($result->user_id)->firstOrFail(['id']),
                'order_approved',
                [
                    'id' => $result->id,
                    'type' => 'agency_order',
                ]
            );

            return $this->okResponse(
                message: translate_success_message('order', 'approved')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function cancelOrder(int $order): JsonResponse
    {
        $order = Order::whereHas('agencyProduct')
            ->whereId($order)
            ->whereStatus(OrderHelper::pendingStatus())
            ->firstOrFail(['id', 'user_id']);

        $order->update(['status' => OrderHelper::canceledStatus()]);

        // Order Canceled Event
        NotificationHelper::notifyUser(
            User::whereId($order->user_id)->firstOrFail(['id']),
            'order_canceled',
            [
                'id' => $order->id,
                'type' => 'agency_order',
            ]
        );

        return $this->okResponse(
            translate_success_message('order', 'canceled')
        );
    }
}
