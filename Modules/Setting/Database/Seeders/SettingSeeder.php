<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Setting\Entities\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'title' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->url(),
            'twitter' => fake()->url(),
            'instagram' => fake()->url(),
            'linkedin' => fake()->url(),
            'facebook' => fake()->url(),
            'whatsapp' => fake()->phoneNumber(),
            'youtube' => fake()->url(),
            'working_hours' => fake()->sentence(),
        ]);
    }
}
