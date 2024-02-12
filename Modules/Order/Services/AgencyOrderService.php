<?php

namespace Modules\Order\Services;

use Modules\DeliveryMan\Entities\DeliveryMan;
use Modules\Order\Entities\Order;
use Modules\Order\Helpers\OrderHelper;
use Modules\Product\Entities\Product;

class AgencyOrderService
{
    public function index()
    {
        return Order::whereHas('agencyProduct')
            ->withProductDetails()
            ->latest()
            ->get(['id', 'product_id', 'order_details->price as price', 'status', 'created_at']);
    }

    public function show(int $order)
    {
        return Order::whereHas('agencyProduct')
            ->with(
                [
                    'product' => function ($query) {
                        $query->select(['id', 'name', 'description', 'category_id']);
                        $query
                            ->withCategory()
                            ->withMainImage();
                    },
                    'features.feature:id,name',
                    'coupon:id,name,percentage',
                ]
            )
            ->whereId($order)
            ->firstOrFail([
                'id',
                'product_id',
                'coupon_id',
                'status',
                'order_details',
                'created_at',
            ]);
    }

    public function approveOrder(array $data)
    {
        $errors = [];
        $order = Order::whereHas('agencyProduct')
            ->whereId($data['order'])
            ->whereStatus(OrderHelper::pendingStatus())
            ->withProduct('id,quantity')
            ->firstOrFail(['id', 'order_details', 'product_id', 'user_id']);

        //TODO Check if delivery man exists
        $deliveryMan = $this->getDeliveryMan($data['delivery_man_id'], $errors);

        if ($errors) {
            return $errors;
        }

        //TODO Decrease the quantity of the original product
        $product = $order->product;

        $this->hasEnoughQuantity($product->quantity, $order->order_details['quantity'], $errors);

        if ($errors) {
            return $errors;
        }

        //TODO Make the order
        $this->updateProductQuantity($product, $order->order_details['quantity']);
        $this->updateOrder($order, $deliveryMan, $data['delivers_at']);

        return $order;
    }

    protected function hasEnoughQuantity(int $totalQuantity, int $comparedQuantity, array &$errors): void
    {
        if ($totalQuantity < $comparedQuantity) {
            $errors['quantity'] = translate_word('has_big_quantity', ['existingQuantity' => $totalQuantity]);
        }

    }

    protected function getDeliveryMan(int $deliveryMan, array &$errors)
    {
        return DeliveryMan::whereUserId(auth()->id())
            ->whereId($deliveryMan)
            ->firstOr(['id', 'name', 'phone', 'delivery_license'], function () use (&$errors) {
                $errors['delivery_man_id'] = translate_error_message('delivery_man', 'not_exists');
            });
    }

    protected function updateProductQuantity(Product $product, float $orderQuantity): void
    {
        $product->quantity -= $orderQuantity;
        $product->save();
    }

    protected function updateOrder(Order $order, DeliveryMan $deliveryMan, string $deliversAt)
    {
        $orderDetails = $order->order_details;
        $orderDetails['delivers_at'] = $deliversAt;

        $order->update([
            'status' => OrderHelper::approvedStatus(),
            'order_details' => $orderDetails,
            'delivery_man_id' => $deliveryMan->id,
        ]);
        $order->setRelation('deliveryMan', $deliveryMan);
    }
}
