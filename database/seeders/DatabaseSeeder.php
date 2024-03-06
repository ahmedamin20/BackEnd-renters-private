<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\AboutUs\Database\Seeders\AboutUsDatabaseSeeder;
use Modules\Ad\Database\Seeders\AdDatabaseSeeder;
use Modules\Auth\Database\Seeders\UserSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\ContactUs\Database\Seeders\ContactUsDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Role\Database\Seeders\PermissionTableSeeder;
use Modules\Setting\Database\Seeders\SettingSeeder;
use Modules\TermsAndConditions\Database\Seeders\TermsAndConditionsDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AboutUsDatabaseSeeder::class,
            SettingSeeder::class,
            TermsAndConditionsDatabaseSeeder::class,
//            AdDatabaseSeeder::class,
//            ContactUsDatabaseSeeder::class,
            PermissionTableSeeder::class,
            UserSeeder::class,
            CategoryDatabaseSeeder::class,
//            ProductDatabaseSeeder::class,
//            OrderDatabaseSeeder::class,
        ]);
    }
}
