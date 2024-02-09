<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\SocialAuthEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class SocialiteService
{
    /**
     * @throws FileCannotBeAdded
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function handleProviderCallback(array $data)
    {
        $errors = [];
        $requestProvider = $data['provider'];
        $accessToken = $data['access_token'];
        $secret = $data['secret'] ?? null;

        try {
            if ($requestProvider == SocialAuthEnum::TWITTER) {

                $user = Socialite::driver(SocialAuthEnum::TWITTER)->userFromTokenAndSecret($accessToken, $secret);

            } else {
                $user = Socialite::driver($requestProvider)->stateless()->userFromToken($accessToken);
            }

        } catch (Exception) {
            $errors['invalid_credentials'] = translate_word('invalid_credentials');

            return $errors;
        }

        $email = $user->getEmail();

        if (! $email) {
            $errors['email'] = translate_error_message('email', 'required');

            return $errors;
        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if (! $existingUser) {

            $existingUser = User::create([
                'email' => $email,
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
                'password' => null,
                'type' => UserTypeEnum::numericType(UserTypeEnum::USER),
                'social_provider' => $requestProvider,
            ]);

            $avatar = $existingUser
                ->addMediaFromUrl($user->getAvatar())
                ->toMediaCollection(AuthEnum::AVATAR_COLLECTION_NAME);

            $existingUser = User::whereId($existingUser->id)->first();

            $existingUser->setRelation('avatar', new Collection([$avatar]));
        }

        $existingUser->loadMissing('avatar');

        $existingUser->token = $existingUser->createToken('API TOKEN')->plainTextToken;

        return $existingUser;
    }
}
