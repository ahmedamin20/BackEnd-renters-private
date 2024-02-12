<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Product\Entities\Product;

class ProductDatabaseSeeder extends Seeder
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

        for ($i = 0; $i < static::$recordCount; $i++) {
            Product::create([
                'name' => fake()->name(),
                'price' => fake()->randomFloat(2, 1, 500),
                'description' => fake()->text(),
                'rating_average' => fake()->randomFloat(1, 0, 5),
                'minimum_days' => fake()->numberBetween(1, 10),
                'maximum_days' => fake()->numberBetween(11, 20),
                'health' => fake()->numberBetween(1, 5),
                'user_id' => 2,
                'category_id' => fake()->numberBetween(1, CategoryDatabaseSeeder::$recordCount),
            ]);
        }
    }
}
