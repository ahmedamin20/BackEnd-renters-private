<?php

namespace Modules\TermsAndConditions\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\TermsAndConditions\Entities\TermAndCondition;

class TermsAndConditionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        TermAndCondition::create(['content' => fake()->realText(5000)]);
    }
}
