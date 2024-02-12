<?php

namespace Modules\Order\Database\Seeders;

use App\Helpers\DateHelper;
use App\Helpers\UserHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Coupon\Database\Seeders\CouponDatabaseSeeder;
use Modules\DeliveryMan\Database\Seeders\DeliveryManDatabaseSeeder;
use Modules\Order\Entities\Order;
use Modules\Order\Helpers\OrderHelper;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;

class OrderDatabaseSeeder extends Seeder
{
    public static int $recordCount = 100;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $orders = [];
        $availableStates = array_keys(OrderHelper::availableStates());
        $availableStatesCount = count(OrderHelper::availableStates());

        for ($i = 0; $i < static::$recordCount; $i++) {
            $coupon = [null, fake()->numberBetween(1, CouponDatabaseSeeder::$recordCount)];
            $orderStatus = $availableStates[fake()->numberBetween(0, $availableStatesCount - 1)];
            $orders[] = [
                'status' => $orderStatus,
                'product_id' => fake()->numberBetween(1, ProductDatabaseSeeder::$recordCount),
                'user_id' => UserHelper::fakeClientId(),
                'coupon_id' => $coupon[fake()->numberBetween(0, 1)],
                'delivery_man_id' => $orderStatus == OrderHelper::approvedStatus()
                    ? fake()->numberBetween(1, DeliveryManDatabaseSeeder::$recordCount)
                    : null,
                'order_details' => json_encode([
                    'name' => fake()->name(),
                    'address' => fake()->address(),
                    'phone' => fake()->phoneNumber(),
                    'price' => fake()->numberBetween(1, 500),
                    'quantity' => fake()->numberBetween(1, 500),
                    'old_product_price' => fake()->randomFloat(2, 1, 500),
                    'delivers_at' => $orderStatus == OrderHelper::approvedStatus()
                        ? now()->format(DateHelper::defaultDateFormat())
                        : '',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($orders, 1000) as $orderGroup) {
            Order::insert($orderGroup);
        }
    }
}
