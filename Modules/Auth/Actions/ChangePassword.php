<?php

namespace Modules\Auth\Actions;

use App\Models\User;

class ChangePassword
{
    /**
     * Change User Password
     *
     * <p>This Method Handles <b>Changing User Password</b> In Profile For Example</p>
     */
    public function handle(array $data, $user): bool
    {
        $user = User::where('id', $user)->first();
        $user->update([
            'password' => $data['new_password'],
        ]);

        return true;
    }
}
