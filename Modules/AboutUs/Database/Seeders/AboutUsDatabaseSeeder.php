<?php

namespace Modules\AboutUs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AboutUs\Entities\AboutUs;

class AboutUsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        AboutUs::create([
            'name' => fake()->company(),
            'youtube_video_url' => 'https://www.youtube.com',
            'description' => fake()->realText(),
        ]);
    }
}
