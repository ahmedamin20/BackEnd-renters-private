<?php

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\User\Database\Seeders\UserSeeder;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->call([
            PermissionTableSeeder::class,
            UserSeeder::class,
        ]);
    }
}
