<?php

namespace Modules\ContactUs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ContactUs\Entities\ContactUs;

class ContactUsDatabaseSeeder extends Seeder
{
    public static int $recordCount = 100;

    public function run(): void
    {
        $data = [];

        for ($i = 0; $i < static::$recordCount; $i++) {
            $data[] = [
                'name' => fake()->name(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'message' => fake()->realText(),
                'status' => 0,
                'created_at' => now(),
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            ContactUs::insert($chunk);
        }
    }
}
