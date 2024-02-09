<?php

namespace Modules\Auth\Services\PassCode;

use App\Models\User;
use Illuminate\Support\Str;

class UserPassCodeService
{
    public function store(array $data)
    {
        $errors = [];

        $currentPassCode = auth()->user()->pass_code;

        if (! is_null($currentPassCode)) {
            $errors['pass_code'] = translate_word('pass_code_created_before');

            return $errors;
        }

        return $this->generatePassCodeToken($data['pass_code']);
    }

    public function update(array $data)
    {
        $errors = [];

        //TODO check if user has pass code or not

        if (! $this->validatePassCode($data['current_pass_code'])) {
            $errors['current_pass_code'] = translate_word('wrong_pass_code');

            return $errors;
        }

        auth()->user()->update([
            'pass_code' => hash('sha256', $data['new_pass_code']),
        ]);

        return true;
    }

    public function validate(array $data)
    {
        $errors = [];

        if (! $this->validatePassCode($data['pass_code'])) {
            $errors['pass_code'] = translate_word('wrong_pass_code');

            return $errors;
        }

        return $this->generatePassCodeToken($data['pass_code']);
    }

    public function validatePassCode($passCode, User $user = null): bool
    {
        $user = $user ?: auth()->user();

        return $user->pass_code === hash('sha256', $passCode);
    }

    private function generatePassCodeToken($passCode): array
    {
        auth()->user()->update(['pass_code' => hash('sha256', $passCode)]);
        auth()->user()->passCodes()->delete();
        $randomToken = Str::random(40);

        auth()->user()->passCodes()->create([
            'token' => hash('sha256', $randomToken),
        ]);

        return [
            'token' => $randomToken,
        ];
    }
}
