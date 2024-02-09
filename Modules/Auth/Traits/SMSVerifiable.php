<?php

namespace Modules\Auth\Traits;

use App\Models\User;
use Modules\Auth\Entities\VerifyUserModel;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserStatusEnum;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

trait SMSVerifiable
{
    public function verify(string $handle, string $code, User $user = null)
    {
        $errors = [];
        if (is_null($user)) {
            $user = User::wherePhone($handle)->firstOr(function () use (&$errors) {
                $errors['handle'] = translate_error_message('user', 'not_exists');
            });
        }

        if (! $errors) {

            $shouldVerifyUser = $this->shouldVerifyUser($user);
            if (is_bool($shouldVerifyUser) && $shouldVerifyUser) {

                // Checking If The Code Exists

                $userVerifyCode = VerifyUserModel::whereHandle($handle)
                    ->whereCode(hash('sha256', $code))
                    ->where('expire_at', '>', now())
                    ->first();

                if ($userVerifyCode) {
                    $this->makeUserVerified($user);
                    $userVerifyCode->delete();

                    return true;
                } else {
                    $errors['user_verify_not_found'] = translate_word('user_verify_not_found');
                }
            } else {
                if (is_bool($shouldVerifyUser)) {
                    $errors['user_already_verified'] = translate_word('user_already_verified');
                }
            }
        }

        return $errors;
    }

    public function shouldVerifyUser(User|int $user): bool|array
    {
        $errors = [];
        $user = $user instanceof User
            ? $user
            : User::whereId(auth()->id())->firstOr(['id', 'phone_verified_at'], function () use (&$errors) {
                $errors['handle'] = translate_error_message('handle', 'not_found');
            });

        return $errors ?: is_null($user->phone_verified_at);
    }

    public function makeUserVerified(User|int $user): void
    {
        $user = $user instanceof User ? $user : User::whereId($user)->firstOrFail(['id']);
        $user->forceFill([
            'phone_verified_at' => now(),
        ]);
        $user->status = UserStatusEnum::ACTIVE;
        $user->save();
    }

    public function resendVerifyUser(User|string $receiver)
    {
        $errors = [];
        //TODO Start Updating User Verify Token
        $receiver = $receiver instanceof User
            ? $receiver
            : User::wherePhone($receiver)->firstOr(function () use (&$errors) {
                $errors['handle'] = translate_error_message('handle', 'not_exists');
            });

        if ($receiver) {

            if ($this->shouldVerifyUser($receiver)) {
                $verifyCode = fake()->numberBetween(1000, 9999);
                $expireDate = now()->addHours(2);
                VerifyUserModel::updateOrCreate([
                    'handle' => $receiver->phone,
                ], [
                    'handle' => $receiver->phone,
                    'code' => hash('sha256', $verifyCode),
                    'expire_at' => $expireDate,
                ]);

                $expireDate = $expireDate->format('Y-m-d h:i');
                $message = translate_word('register_sms', [
                    'name' => $receiver->name,
                    'code' => $verifyCode,
                    'expire_date' => $expireDate,
                ]);
                $this->sendMessage("+{$receiver->phone}", $message);

                return true;

            } else {
                $errors['user_already_verified'] = translate_word('user_already_verified');
            }
        }

        return $errors;

    }

    /**
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public function sendMessage(string $receiver, string $body)
    {
        //        $twilioFromNumber = config('services.twilio.phone_number');
        $twilioAccountSID = config('services.twilio.account_sid');
        $twilioAuthToken = config('services.twilio.auth_token');
        $twilioMessagingServiceID = config('services.twilio.service_sid');

        $twilioClient = new Client($twilioAccountSID, $twilioAuthToken);

        $message = $twilioClient->messages
            ->create($receiver, [
                'messagingServiceSid' => $twilioMessagingServiceID,
                'body' => $body,
                'forceDelivery' => true,
            ]);

        return $message->status;
    }

    private function sendMessageInTesting(string $receiver, string $message)
    {
        $twilioAccountSID = config('services.twilio.account_sid');
        $twilioAuthToken = config('services.twilio.auth_token');
        $twilioServiceSID = config('services.twilio.service_sid');

        $twilio = new Client($twilioAccountSID, $twilioAuthToken);
        $verification = $twilio->verify->v2->services($twilioServiceSID)
            ->verifications
            ->create($receiver, 'sms');

        return $verification->valid;
    }

    private function verifyUserTesting($user, $handle, $code)
    {
        $twilioFromNumber = config('services.twilio.phone_number');
        $twilioAccountSID = config('services.twilio.account_sid');
        $twilioAuthToken = config('services.twilio.auth_token');
        $twilioServiceSID = config('services.twilio.service_sid');
        $twilio = new Client($twilioAccountSID, $twilioAuthToken);
        $verification = $twilio->verify->v2->services($twilioServiceSID)
            ->verificationChecks
            ->create(['to' => "+{$user->phone}", 'code' => $code]);

        //        $userVerifyCode = VerifyUserModel::whereHandle($handle)
        //            ->first();
        return $verification->valid;
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
}
