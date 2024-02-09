<?php

namespace Modules\Auth\Tests;

use App\Models\User;

class AuthHelper
{
    public static function createUser(): mixed
    {
        return User::create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'Aa2302$#@',
            'email_verified_at' => now(),
        ]);
    }
}
