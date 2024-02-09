<?php

namespace Modules\Auth\Services\PassCode;

use App\Models\User;
use Modules\Auth\Enums\UserTypeEnum;

class AdminPassCodeService
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function change(array $data)
    {
        $errors = [];

        $user = $this->userModel::whereId($data['user_id'])
            ->whereType(UserTypeEnum::USER)
            ->first();

        if (! $user) {
            $errors['user_id'] = translate_success_message('user', 'not_exists');

            return $errors;
        }

        $user->pass_code = hash('sha256', $data['new_pass_code']);
        $user->save();

        return true;
    }
}
