<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Actions\LogoutUser;

class LogoutController extends Controller
{
    use HttpResponse;

    public function __invoke(): JsonResponse
    {
        (new LogoutUser())->handle();

        return $this->okResponse(
            message: translate_success_message('user', 'logged_out')
        );
    }
}
