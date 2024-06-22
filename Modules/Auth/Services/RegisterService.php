<?php

namespace Modules\Auth\Services;

use App\Jobs\ProcessNationalIdJob;
use App\Models\User;
use App\Services\FileOperationService;
use DB;
use Exception;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
                        'identity_verified' => false,
                    ]
                );

                $this->queueNationalIdValidation($user);

                $userInfo = (new VerifyEmailService())->verifyUserInfo($user);
                Mail::to($user->email)->send(new VerifyUserEmail($userInfo));
            });
        } catch (Exception $e) {
            $errors['operation_failed'] = $e->getMessage();

            return $errors;
        }

        return true;
    }

    private function queueNationalIdValidation($user)
    {
        $fileOperationService = new FileOperationService();
        $fileOperationService->storeImageFromRequest($user, 'avatar', 'avatar');
        $fileOperationService->storeImageFromRequest($user, 'frontNational', 'front_national_id');
        $fileOperationService->storeImageFromRequest($user, 'backNational', 'back_national_id');
        $front = $user->getMedia('frontNational')->first();
        $back = $user->getMedia('backNational')->first();
        $frontOutput = storage_path('model/'.Str::random(15).'.json');
        $backOutput = storage_path('model/'.Str::random(15).'.json');
        dispatch(new ProcessNationalIdJob($user, $front->getPath(), $frontOutput));
        dispatch(new ProcessNationalIdJob($user, $back->getPath(), $backOutput, 'back'));
    }
}
