<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPassword
{
    public function handle(array $data): mixed
    {
        return Password::reset($data, function (User $user, string $password) {

            // Update User Password
            $user->forceFill([
                'password' => $password, // Here We don't hash password because it got hashed in `User` model
            ])
                ->setRememberToken(Str::random(50));

            $user->save();

            // Fire The Reset Password Event

            event(new PasswordReset($user));
        });
    }
}
