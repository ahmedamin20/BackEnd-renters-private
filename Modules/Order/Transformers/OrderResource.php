<?php

namespace Modules\Order\Transformers;

use App\Helpers\DateHelper;
use App\Helpers\ResourceHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Helpers\OrderHelper;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'orderId' => $this->id,
            'status' => OrderHelper::getTranslatedStatus((int) $this->status),
            $this->mergeWhen(
                ! is_null($this->price),
                ['price' => round($this->price, 2)]
            ),
            'createdAt' => $this->when(
                ! is_null($this->created_at),
                DateHelper::getFormattedDate($this->created_at)
            ),
            $this->mergeWhen(is_array($this->order_details), function () {
                $orderDetails = $this->order_details;

                return [
                    'quantity' => (int) $orderDetails['quantity'],
                    'price' => round($orderDetails['price'], 2),
                    $this->merge($this->getCustomerDetails($this)),
                ];
            }),
            $this->mergeWhen($this->relationLoaded('coupon'), function () {
                $couponDetails = null;

                if (! is_null($this->coupon)) {
                    $couponDetails = [
                        'name' => $this->coupon->name,
                        'percentage' => $this->coupon->percentage,
                        'oldProductPrice' => round($this->order_details['old_product_price'] ?? 0, 2),
                    ];
                }

                return [
                    'coupon' => $couponDetails,
                ];
            }),
            $this->mergeWhen($this->relationLoaded('product'), function () {
                return $this->getProductDetails($this);
            }),
            $this->mergeWhen($this->relationLoaded('features'), function () {
                return $this->getOrderFeatures($this);
            }),

            $this->mergeWhen($this->relationLoaded('deliveryMan'), function () {
                return $this->getOrderDeliveryMan($this);
            }),
        ];
    }

    public function getCustomerDetails($object): array
    {
        $orderDetails = $object->order_details;

        return [
            'customerDetails' => [
                'name' => $orderDetails['name'],
                'phone' => $orderDetails['phone'],
                'address' => $orderDetails['address'],
            ],
        ];
    }

    public function getProductDetails($object): array
    {
        $product = $object->product;

        return [
            'productDetails' => [
                'name' => $product->name,
                $object->mergeWhen($product->relationLoaded('main_image'), function () use ($product) {
                    return [
                        'mainImage' => ResourceHelper::getFirstMediaOriginalUrl($product, 'main_image'),
                    ];
                }),
                $object->mergeWhen($product->relationLoaded('category'), function () use ($product) {

                    return ['categoryName' => $product->category->name];
                }),
                'description' => $product->description,
            ],
        ];
    }

    public function getOrderFeatures($object): array
    {
        $features = [];
        foreach ($object->features as $feature) {
            if ($feature->relationLoaded('feature')) {
                $features[] = $feature->feature->name;
            }
        }

        return ['features' => $features];
    }

    protected function getOrderDeliveryMan($object)
    {
        $deliveryMan = $object->deliveryMan;

        return [
            'deliveryMan' => $deliveryMan ? [
                'name' => $deliveryMan->name,
                'phone' => $deliveryMan->phone,
                'license' => $deliveryMan->delivery_license,
                'delivers_at' => $object->order_details['delivers_at'] ?? '',
            ] : null,
        ];
    }
}
