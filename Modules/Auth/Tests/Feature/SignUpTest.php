<?php

namespace Modules\Auth\Tests\Feature;

use App\Helpers\ValidationMessageHelper;
use App\Models\User;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Auth\Facades\AuthConfig;
use Modules\Auth\Tests\TestCase;
use Modules\GeneralConfig;

class SignUpTest extends TestCase
{
    public function testSignUpCorrectly()
    {
        GeneralConfig::setStopOnFirstFailure();
        $response = $this->postJson(route(AuthConfig::getRouteName('register')), $this->getRegisterCredentials());
        $response->assertCreated();
    }

    public function testSignUpWithNoPayload()
    {
        GeneralConfig::setStopOnFirstFailure();

        // Test if User Want To Register As Agency
        $response = $this->postJson(route(AuthConfig::getRouteName('register')));
        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'phone',
                'type',
                'password',
                'role',
            ],
        ]);
        $response = $this->postJson(route(AuthConfig::getRouteName('register'), ['type' => 1]));
        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'phone',
                'password',
                'address',
                'avatar',
                'role',
            ],
        ]);
    }

    public function InvalidEmail()
    {
        GeneralConfig::setStopOnFirstFailure();

        // Invalid Email
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(['email' => 'A@a.com'])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'data' => [
                'email',
            ],
        ]);

        $user = User::create($this->getRegisterCredentials());

        // Email already taken
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(['email' => $user->email])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'data' => [
                'email',
            ],
        ]);
    }

    public function testInvalidName()
    {
        GeneralConfig::setStopOnFirstFailure();

        // Name Must Be String
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(['name' => 1])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'data' => ['name'],
        ]);

        // Name too long
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(['name' => Str::random(300)])
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure([
            'data' => ['name'],
        ]);
    }

    public function testInvalidPassword()
    {
        GeneralConfig::setStopOnFirstFailure();
        // Short Password
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(
                [
                    'password' => 'a',
                    'password_confirmation' => 'a',
                ]
            )
        );

        $response->assertUnprocessable();
        $response->assertJsonStructure(['data' => ['password']]);

        // Not Mixed Case Password
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(
                [
                    'password' => 'aaaaaaaaaaaaaaaaaaaaaa',
                    'password_confirmation' => 'aaaaaaaaaaaaaaaaaaaaaa',
                ]
            )
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(['data' => ['password']]);

        // Has No Symbols
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(
                [
                    'password' => 'Aaaaaaaaaaaaaaaaaaaaaa',
                    'password_confirmation' => 'Aaaaaaaaaaaaaaaaaaaaaa',
                ]
            )
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(['data' => ['password']]);

        // Has No Symbols
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(
                [
                    'password' => 'Aaaaaaaaaaaaaaaaaaaaaa',
                    'password_confirmation' => 'Aaaaaaaaaaaaaaaaaaaaaa',
                ]
            )
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(['data' => ['password']]);

        // Has No Symbols
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(
                [
                    'password' => 'Aaaaaaaaaaaaaaaaaaaaaa',
                    'password_confirmation' => 'AaaaaaaaaaaaaaaaaaaaaaA',
                ]
            )
        );
        $response->assertUnprocessable();
        $response->assertJsonStructure(['data' => ['password']]);
    }

    public function Role()
    {
        $response = $this->postJson(
            route(AuthConfig::getRouteName('register')),
            $this->getRegisterCredentials(
                [
                    'password' => 'Aaaaaaaaaaaaaaaaaaaaaa',
                    'password_confirmation' => 'Aaaaaaaaaaaaaaaaaaaaaa',
                ]
            )
        );
    }

    public function testAvatar()
    {
        Storage::fake();

        // upload non-image file
        $image = UploadedFile::fake()->image('avatar.pdf')->size(1000);
        $response = $this->postJson(route('register'), $this->getRegisterCredentials([
            'avatar' => $image,
        ]));
        $response->assertUnprocessable();
        $response->assertJsonFragment([
            'data' => [
                'avatar' => ValidationMessageHelper::imageErrorMessages()['avatar.image'],
            ],
        ]);

        // upload very big file
        $image = UploadedFile::fake()->image('main.png')->size(15000);
        $response = $this->postJson(route('register'), $this->getRegisterCredentials([
            'avatar' => $image,
        ]));
        $response->assertUnprocessable();
        $response->assertJsonFragment([
            'data' => [
                'avatar' => str_replace(
                    ':max',
                    10000,
                    ValidationMessageHelper::imageErrorMessages()['avatar.max']
                ),
            ],
        ]);

        // Upload File Successfully
        $image = UploadedFile::fake()->image('avatar.png')->size(10000);
        $userInfo = $this->getRegisterCredentials([
            'avatar' => $image,
        ]);
        $response = $this->postJson(route('register'), $this->getRegisterCredentials($userInfo));
        $response->assertCreated();

    }

    protected function getRegisterCredentials(array $testingPayload = []): array
    {
        Storage::fake();
        $image = UploadedFile::fake()->image('avatar.png')->size(10000);
        $payload = [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'password' => 'Aa2302$#@',
            'password_confirmation' => 'Aa2302$#@',
            'address' => 'This is Sample Address',
            'avatar' => $image,
            'type' => 1,
            'role' => 2,
        ];

        foreach ($testingPayload as $key => $value) {
            $payload[$key] = $value;
        }

        return $payload;
    }
}
