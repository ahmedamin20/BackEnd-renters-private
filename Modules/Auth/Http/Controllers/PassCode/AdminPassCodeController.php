<?php

namespace Modules\Auth\Http\Controllers\PassCode;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\PassCode\AdminPassCodeRequest;
use Modules\Auth\Services\PassCode\AdminPassCodeService;

class AdminPassCodeController extends Controller
{
    use HttpResponse;

    public function __invoke(AdminPassCodeRequest $request, AdminPassCodeService $adminPassCodeService): JsonResponse
    {
        $result = $adminPassCodeService->change($request->validated());

        if (is_bool($result)) {
            return $this->okResponse(message: translate_success_message('pass_code', 'updated'));
        }

        return $this->validationErrorsResponse($result);
    }
}
