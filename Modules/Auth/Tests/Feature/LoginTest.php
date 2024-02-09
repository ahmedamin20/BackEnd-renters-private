<?php

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Facades\AuthConfig;
use Modules\Auth\Facades\IsEnabled;
use Modules\Auth\Tests\TestCase;
use Modules\GeneralConfig;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testLoginInMobileSuccessfully(): void
    {
        GeneralConfig::setStopOnFirstFailure();

        $response = $this->postJson(
            route(AuthConfig::getRouteName('login.mobile')),
            ['email' => 'test@example.com', 'password' => 'password']
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'token',
            ] + (IsEnabled::avatar() ? ['avatar'] : []),
        ]);
    }

    public function testWithNoCredentials()
    {
        GeneralConfig::setStopOnFirstFailure();

        $response = $this->postJson(
            route(AuthConfig::getRouteName('login.mobile')),
        );

        $response->assertUnprocessable();

        $response->assertJsonStructure([
            'data' => [
                'email',
                'password',
            ],
        ]);
    }

    public function testLoginWithWrongCredentials()
    {
        GeneralConfig::setStopOnFirstFailure();

        // Invalid Password
        $response = $this->postJson(
            route(AuthConfig::getRouteName('login.mobile')),
            ['email' => 'test@example.com', 'password' => 'asd']
        );
        $response->assertUnauthorized();
        $response->assertJsonFragment([
            'message' => translate_word('wrong_credentials'),
        ]);

        // Invalid Email
        $response = $this->postJson(
            route(AuthConfig::getRouteName('login.mobile')),
            ['email' => 'test@example.coms', 'password' => 'password']
        );
        $response->assertUnauthorized();
        $response->assertJsonFragment([
            'message' => translate_word('wrong_credentials'),
        ]);
    }
}
