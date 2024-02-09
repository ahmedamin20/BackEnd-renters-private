<?php

namespace Modules\Auth\Services;

use App\Models\User;
use DB;
use Exception;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\VerifyUserEmail;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserStatusEnum;
use Modules\Auth\Enums\UserTypeEnum;

class RegisterService
{
    public function registerNewUser(array $data)
    {
        $errors = [];
        $alphaType = UserTypeEnum::USER;

        $data['type'] = $alphaType;

        try {
            DB::transaction(function () use ($data) {
                $user = User::create(
                    $data +
                    [
                        'status' => UserStatusEnum::ACTIVE,
                        AuthEnum::VERIFIED_AT => null,
                    ]
                );

                $userInfo = (new VerifyEmailService())->verifyUserInfo($user);

                Mail::to($user->email)->send(new VerifyUserEmail($userInfo));
            });
        } catch (Exception $e) {
            $errors['operation_failed'] = $e->getMessage();

            return $errors;
        }

        return true;
    }
}
