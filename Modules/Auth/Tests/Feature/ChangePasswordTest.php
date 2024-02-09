<?php

namespace Modules\Auth\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Modules\Auth\Facades\AuthConfig;
use Modules\Auth\Tests\AuthHelper;
use Modules\GeneralConfig;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    public function testChangingPasswordSuccessfully(): void
    {
        GeneralConfig::setStopOnFirstFailure();

        $user = AuthHelper::createUser();

        Sanctum::actingAs($user);
        $response = $this->putJson(
            route(AuthConfig::getRouteName('change_password')),
            $this->getChangePasswordPayload()
        );

        $response->assertOk();
    }

    /**
     * @return string[]
     */
    protected function getChangePasswordPayload(array $testingPayload = []): array
    {
        $payload = [
            'old_password' => 'Aa2302$#@',
            'new_password' => 'Aa2302$#@',
            'new_password_confirmation' => 'Aa2302$#@',
        ];

        foreach ($testingPayload as $key => $value) {
            $payload[$key] = $value;
        }

        return $payload;
    }

    public function testCheckingRequiredParameters(): void
    {
        GeneralConfig::setStopOnFirstFailure();

        $user = AuthHelper::createUser();
        Sanctum::actingAs($user);

        $response = $this->putJson(
            route(AuthConfig::getRouteName('change_password'))
        );

        $response->assertUnprocessable();
        $response->assertJsonStructure(
            [
                'data' => ['old_password', 'new_password'],
            ]
        );
    }

    public function testCheckingPasswordRules(): void
    {
        GeneralConfig::setStopOnFirstFailure();

        $user = AuthHelper::createUser();
        Sanctum::actingAs($user);

        // Password Must Be At least 8 characters
        $response = $this->putJson(
            route(AuthConfig::getRouteName('change_password')),
            $this->getChangePasswordPayload([
                'new_password' => 'a',
                'new_password_confirmation' => 'a',
            ])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(
            [
                'data' => [
                    'new_password',
                ],
            ]
        );

        // Password Must Be In Mixed Case
        $response = $this->putJson(
            route(AuthConfig::getRouteName('change_password')),
            $this->getChangePasswordPayload([
                'new_password' => 'aaaaaaaaaaaaaaaaaaaa',
                'new_password_confirmation' => 'aaaaaaaaaaaaaaaaaaaa',
            ])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(
            [
                'data' => [
                    'new_password',
                ],
            ]
        );

        // Password Must Contain At Least One Symbol
        $response = $this->putJson(
            route(AuthConfig::getRouteName('change_password')),
            $this->getChangePasswordPayload([
                'new_password' => 'Aaaaaaaaaaaaaaaaaaaaa',
                'new_password_confirmation' => 'Aaaaaaaaaaaaaaaaaaaaa',
            ])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(
            [
                'data' => [
                    'new_password',
                ],
            ]
        );

        // Password Confirmation Not Matching
        $response = $this->putJson(
            route(AuthConfig::getRouteName('change_password')),
            $this->getChangePasswordPayload([
                'new_password' => 'Googler#',
                'new_password_confirmation' => 'Aaaaaaaaaaaaaaaaaaaaa',
            ])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(
            [
                'data' => [
                    'new_password',
                ],
            ]
        );

        // Old Password Is Wrong
        $response = $this->putJson(
            route(AuthConfig::getRouteName('change_password')),
            $this->getChangePasswordPayload([
                'old_password' => 'Google',
                'new_password' => 'Googler#',
                'new_password_confirmation' => 'Googler#',
            ])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(
            [
                'data' => [
                    'old_password',
                ],
            ]
        );
    }
}
