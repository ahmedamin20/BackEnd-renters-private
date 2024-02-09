<?php

namespace Modules\Auth\Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Facades\AuthConfig;
use Modules\GeneralConfig;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function testExample()
    {
        GeneralConfig::setStopOnFirstFailure();

        $user = User::create($this->getProfilePayload());
        Sanctum::actingAs($user);

        $response = $this->postJson(
            route(AuthConfig::getRouteName('profile'), $this->getProfilePayload())
        );

        $response->assertOk();
    }

    protected function getProfilePayload(array $testingPayload = []): array
    {
        $payload = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'Aa2302$#@',
            'password_confirmation' => 'Aa2302$#@',
        ];

        foreach ($testingPayload as $key => $value) {
            $payload[$key] = $value;
        }

        return $payload;
    }
}
