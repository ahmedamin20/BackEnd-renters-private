<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Entities\Category;

class CategoryDatabaseSeeder extends Seeder
{
    public static int $recordCount = 100;

    public function run()
    {
        $categories = [];

        for ($i = 0; $i < self::$recordCount; $i++) {
            $categories[] = [
                'name' => fake()->name(),
            ];
        }
        foreach (array_chunk($categories, 50) as $chunk) {
            Category::insert($chunk);
        }
    }
}
