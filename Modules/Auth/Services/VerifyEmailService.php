<?php

namespace Modules\Auth\Services;

use App\Helpers\DateHelper;
use App\Models\User;
use Modules\Auth\Entities\VerifyUserModel;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserStatusEnum;

class VerifyEmailService
{
    public function verify(string $handle, string $code, User $user = null): bool|array
    {
        $errors = [];

        $user = $this->getUser($user ?: $handle, $errors);

        if ($errors) {
            return $errors;
        }

        $shouldVerifyUser = $this->shouldVerifyUser($user);

        if (is_bool($shouldVerifyUser) && ! $shouldVerifyUser) {
            $errors['user_already_verified'] = translate_word('user_already_verified');
        }

        if (! is_bool($errors) && $errors) {
            return $errors;
        }

        // Checking If The Code Exists
        $userVerifyCode = VerifyUserModel::whereHandle($handle)
            ->whereCode(hash('sha256', $code))
            ->where('expire_at', '>', now())
            ->first();

        if (! $userVerifyCode) {
            $errors['user_verify_not_found'] = 'Provided Code Is Wrong, Or Expired';

            return $errors;
        }

        $this->makeUserVerified($user);

        $userVerifyCode->delete();

        return true;
    }

    public function shouldVerifyUser(User|int $user): bool|array
    {
        $errors = [];

        $user = $this->getUser($user, $errors);

        return $errors ?: is_null($user->{AuthEnum::VERIFIED_AT});
    }

    public function makeUserVerified(User|int $user): void
    {
        $user = $user instanceof User ? $user : User::whereId($user)->firstOrFail(['id']);
        $user->forceFill([
            AuthEnum::VERIFIED_AT => now(),
        ]);
        $user->status = UserStatusEnum::ACTIVE;
        $user->save();
    }

    public function verifyUserInfo(User|string $receiver): bool|array
    {
        $errors = [];

        //TODO Start Updating User Verify Token
        $receiver = $this->getUser($receiver, $errors);

        if ($errors) {
            return $errors;
        }

        $shouldVerifyUser = $this->shouldVerifyUser($receiver);

        if (! $this->isShouldVerifyUserValid($shouldVerifyUser, $errors)) {
            return $errors;
        }

        $verifyCode = fake()->numberBetween(1000, 9999);

        $expireAfter = now()->addHours(2);
        VerifyUserModel::updateOrCreate([
            'handle' => $receiver->{AuthEnum::UNIQUE_COLUMN},
        ], [
            'handle' => $receiver->{AuthEnum::UNIQUE_COLUMN},
            'code' => hash('sha256', $verifyCode),
            'expire_at' => $expireAfter,
        ]);

        $expireAfter = $expireAfter->format(DateHelper::defaultDateTimeFormat());

        return [
            'name' => $receiver->name,
            'code' => $verifyCode,
            'expire_after' => 2,
        ];
    }

    public function isMessageSent(string $returnedResponse): bool
    {
        return $returnedResponse == 'accepted';
    }

    public function isUserVerified(User $user = null): bool
    {
        $user = $user ?: auth()->user();

        return (bool) $user->{AuthEnum::VERIFIED_AT};
    }

    private function isShouldVerifyUserValid($shouldVerifyResult, array &$errors): bool
    {
        if (is_bool($shouldVerifyResult) && ! $shouldVerifyResult) {
            $errors['user_already_verified'] = translate_word('user_already_verified');
        }

        if (! is_bool($errors) && $errors) {
            return false;
        }

        return true;
    }

    private function getUser(User|string $user, array &$errors)
    {
        return $user instanceof User
            ? $user
            : User::where(AuthEnum::UNIQUE_COLUMN, $user)
                ->WhereValidType(true)
                ->firstOr(function () use (&$errors) {
                    $errors['handle'] = translate_error_message('handle', 'not_exists');
                });
    }
}
