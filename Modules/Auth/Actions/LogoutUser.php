<?php

namespace Modules\Auth\Actions;

class LogoutUser
{
    public function handle(): bool
    {
        // Revoke All Tokens Of Logged User
        // auth()->user()->tokens()->delete();

        // Revoke current token only
        auth()->user()->currentAccessToken()->delete();
        session()->regenerate(true);
        session()->regenerateToken();

        return true;
    }
}
