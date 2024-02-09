<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Password;

class ForgotPassword
{
    public function handle(array $data): array|string
    {
        $errors = [];
        $user = User::whereEmail($data['email'])->first();

        if ($user) {
            return Password::sendResetLink($data);

        } else {
            $errors['email'] = translate_error_message('email', 'not_found');
        }

        return $errors;
    }
}
