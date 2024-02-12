<?php

namespace Modules\Order\Services;

use Modules\Order\Entities\Order;

class OrderRetrieverService
{
    public function getOrdersForClients(int $userID = null){
        $userID = $userID ?: auth()->id();

        return Order::withProductDetails()
            ->whereUserId($userID)
            ->latest()
            ->get(['id', 'product_id', 'order_details->price as price', 'status']);
    }

    public function getOrderDetailsForClients(int $orderID, int $userID = null){
        $userID = $userID ?: auth()->id();

        return Order::whereUserId($userID)
            ->with(
                [
                    'product' => function ($query) {
                        $query->select(['id', 'name', 'description', 'category_id']);
                        $query->withCategory()->withMainImage();
                    },
                    'features.feature:id,name',
                    'deliveryMan',
                ]
            )
            ->whereId($orderID)
            ->firstOrFail([
                'id',
                'product_id',
                'delivery_man_id',
                'status',
                'order_details',
                'created_at',
            ]);
    }
}