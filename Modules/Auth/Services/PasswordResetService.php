<?php

namespace Modules\Auth\Services;

use App\Models\User;
use DB;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Auth\Emails\VerifyUserEmail;
use Modules\Auth\Entities\PasswordReset;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Traits\SMSVerifiable;

class PasswordResetService
{
    use SMSVerifiable;

    public function forgotPassword(array $data)
    {
        $errors = [];

        try {
            DB::transaction(function () use ($data, &$errors) {
                $handle = $data['handle'];
                $user = User::where(AuthEnum::UNIQUE_COLUMN, $handle)->firstOr(function () use (&$errors) {
                    $errors['handle'] = translate_error_message('handle', 'not_found');
                });

                if ($errors) {
                    return;
                }

                $randomCode = fake()->numberBetween(1000, 9999);
                $expireDate = now()->addHours(2);
                PasswordReset::updateOrCreate([
                    'handle' => $handle,
                ], [
                    'handle' => $handle,
                    'code' => hash('sha256', $randomCode),
                    'expire_at' => $expireDate,
                ]);

                Mail::to($handle)->send(new VerifyUserEmail([
                    'code' => $randomCode,
                    'expire_after' => 2,
                    'name' => $user->name,
                ], 'auth::forgot-password'));
            });
        } catch (Exception) {
            $errors['operation_failed'] = translate_word('operation_failed');
        }

        return $errors ?: true;
    }

    public function resetPassword(array $data): bool|array
    {
        $errors = [];
        $passwordResetExists = PasswordReset::whereHandle($data['handle'])
            ->whereCode(hash('sha256', $data['code']))
            ->where('expire_at', '>', now())
            ->first();

        if (! $passwordResetExists) {
            $errors['code'] = translate_error_message('code', 'not_exists');

            return $errors;
        }

        $user = User::where(AuthEnum::UNIQUE_COLUMN, $data['handle'])->first();

        $user->forceFill([
            'password' => $data['password'],
        ])
            ->setRememberToken(Str::random());

        $user->save();

        $passwordResetExists->delete();

        return true;
    }
}
