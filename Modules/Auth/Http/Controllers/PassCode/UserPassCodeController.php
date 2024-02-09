<?php

namespace Modules\Auth\Http\Controllers\PassCode;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\PassCode\StorePassCodeRequest;
use Modules\Auth\Http\Requests\PassCode\UpdatePassCodeRequest;
use Modules\Auth\Services\PassCode\UserPassCodeService;

class UserPassCodeController extends Controller
{
    use HttpResponse;

    private UserPassCodeService $passCodeService;

    public function __construct()
    {
        $this->passCodeService = new UserPassCodeService();
    }

    public function store(StorePassCodeRequest $request)
    {
        $result = $this->passCodeService->store($request->validated());

        if (isset($result['token'])) {
            return $this->createdResponse($result);
        }

        return $this->validationErrorsResponse($result);
    }

    public function update(UpdatePassCodeRequest $request)
    {
        $result = $this->passCodeService->update($request->validated());

        if (is_bool($result)) {
            return $this->okResponse(message: translate_success_message('pass_code', 'updated'));
        }

        return $this->validationErrorsResponse($result);
    }

    public function validate(StorePassCodeRequest $request)
    {
        $result = $this->passCodeService->validate($request->validated());

        if (isset($result['token'])) {
            return $this->okResponse($result, showToast: false);
        }

        return $this->validationErrorsResponse($result);
    }
}
