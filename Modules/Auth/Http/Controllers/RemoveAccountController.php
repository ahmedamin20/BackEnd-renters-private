<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Actions\LogoutUser;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Http\Requests\DeleteUserRequest;

class RemoveAccountController extends Controller
{
    use HttpResponse;

    public function __invoke(DeleteUserRequest $request): JsonResponse
    {
        $user = auth()->user();

        if (UserTypeEnum::getCurrentUserAlphaType() != UserTypeEnum::ADMIN) {
            (new LogoutUser())->handle();
            $user->delete();

            return $this->okResponse(
                message: translate_success_message('user', 'deleted')
            );
        }

        return $this->notFoundResponse(
            message: translate_error_message('user', 'not_found')
        );
    }
}
