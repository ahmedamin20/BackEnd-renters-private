<?php

namespace Modules\Order\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Enums\OrderStatusEnum;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $from = rand(2, 3);

        return [
            'from_user_id' => $from,
            'to_user_id' => $from == 2 ? 3 : 2,
            'price' => rand(100, 1000),
            'status' => fake()->randomElement(OrderStatusEnum::availableTypes()),
            'from_date' => now()->subDays(rand(1, 10)),
            'to_date' => now()->addDays(rand(1, 10)),
        ];
    }
}

