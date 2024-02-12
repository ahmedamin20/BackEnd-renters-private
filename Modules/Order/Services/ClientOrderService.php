<?php

namespace Modules\Order\Services;

use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\Order;
use Modules\Order\Helpers\OrderHelper;
use Modules\Product\Entities\Product;

class ClientOrderService
{
    public function __construct(
        private readonly OrderRetrieverService $orderRetrieverService
    ){
    }
    public function index()
    {
        return $this->orderRetrieverService->getOrdersForClients();
    }

    public function show(int $order)
    {
        return $this->orderRetrieverService->getOrderDetailsForClients($order);
    }

    public function store(array $data): Order|array
    {
        $errors = [];
        $product = $this->getProductIfExists($data['product_id'], $errors);

        if ($errors) {
            return $errors;
        }

        $this->checkIfAllFeaturesExistsForProduct(
            $product,
            $data['features'],
            $errors
        );

        if (! $this->checkIfProductHasEnoughQuantity($product, $data['quantity'])) {
            $errors['quantity'] = translate_word('has_big_quantity', ['existingQuantity' => $product->quantity]);
        }

        if ($errors) {
            return $errors;
        }

        //TODO Calculate The Price Of The Product
        $coupon = isset($data['coupon'])
            ? $this->findCoupon($data['coupon'], $product->user_id, $errors)
            : null;

        $orderPrice = $this->calculatePrice($product, $coupon);

        if ($errors) {
            return $errors;
        }

        // Make Order
        $order = Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'coupon_id' => $coupon?->id,
            'status' => OrderHelper::pendingStatus(),
            'order_details' => [
                'quantity' => $data['quantity'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'price' => $orderPrice,
                'old_product_price' => $product->price,
            ],
        ]);

        $this->associateFeaturesToOrder($data['features'], $order);;

        return $order;
    }

    private function getProductIfExists(int $product, array &$errors): Model|Product|Builder|null
    {
        return Product::whereId($product)
            ->getAllDataIfNotAgency()
            ->firstOr(['id', 'quantity', 'price', 'user_id'], function () use (&$errors) {
                $errors['product_id'] = translate_error_message(
                    'product',
                    'not_exists'
                );
            });
    }

    private function checkIfAllFeaturesExistsForProduct(
        Product|int $product,
        array $requestFeatures,
        array &$errors,
        int $requestFeaturesCount = null
    ): void {
        $product = $product instanceof Product
            ? $product
            : $this->getProductIfExists($product, $errors);

        if (! $errors) {
            $this->checkAllFeatureExists(
                $product,
                $errors,
                $requestFeatures,
                $requestFeaturesCount
            );
        }
    }

    private function checkAllFeatureExists(
        Product $product,
        array &$errors,
        array $requestFeatures,
        int $requestFeaturesCount = null
    ): void {

        $existingFeatures = $product
            ->features()
            ->whereIn('feature_id', $requestFeatures)
            ->get(['feature_id'])
            ->pluck('feature_id')
            ->toArray();

        $requestFeaturesCount = $requestFeaturesCount ?: count($requestFeatures);

        if (count($existingFeatures) != $requestFeaturesCount) {
            for ($i = 0; $i < $requestFeaturesCount; $i++) {
                if (! in_array($requestFeatures[$i], $existingFeatures)) {
                    $errors["features.$i"] = translate_error_message('feature', 'not_exists');
                }
            }
        }
    }

    private function checkIfProductHasEnoughQuantity(Product $product, int $requestQuantity): bool
    {
        return $product->quantity >= $requestQuantity;
    }

    private function findCoupon(string $promoCode, int $userId, array &$errors): mixed
    {
        return Coupon::whereUserId($userId)
            ->whereName($promoCode)
            ->where('end_date', '>', now()->format(DateHelper::defaultDateFormat()))
            ->whereHasEnoughUsers()
            ->firstOr(['id', 'percentage', 'remaining_users_count'], function () use (&$errors) {
                $errors['coupon'] = translate_error_message('coupon', 'not_exists');
            });
    }

    private function calculatePrice(Product $product, Coupon $coupon = null): float|int
    {
        $totalPrice = $product->price;

        if (! is_null($coupon)) {
            $discountPrice = $this->calculateCouponDiscountPrice($coupon->percentage, $totalPrice);
            $totalPrice -= $discountPrice;
        }

        return round($totalPrice, 2);
    }

    private function calculateCouponDiscountPrice(int $percentage, int $totalPrice): float|int
    {
        return ($percentage * $totalPrice) / 100;
    }

    private function associateFeaturesToOrder(array $features, Order $order): void
    {
        $featuresCount = count($features);

        for ($i = 0; $i < $featuresCount; $i++) {
            $features[$i] = [
                'feature_id' => $features[$i],
                'featurable_type' => Order::class,
                'featurable_id' => $order->id,
            ];
        }

        $order->features()->insert($features);
    }
}
