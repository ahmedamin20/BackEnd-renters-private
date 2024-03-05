<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Order;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Order::factory(500)->create();

        foreach (Order::all() as $order) {
            $order->products()->attach(rand(1, ProductDatabaseSeeder::$recordCount));
        }
    }
}
