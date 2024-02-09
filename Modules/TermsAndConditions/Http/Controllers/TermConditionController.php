<?php

namespace Modules\TermsAndConditions\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\TermsAndConditions\Entities\TermAndCondition;
use Modules\TermsAndConditions\Http\Requests\TermConditionRequest;

class TermConditionController extends Controller
{
    use HttpResponse;

    public function show(): JsonResponse
    {
        return $this->resourceResponse(TermAndCondition::first(['content']));
    }

    public function update(TermConditionRequest $request): JsonResponse
    {
        $termsAndConditions = TermAndCondition::first();
        $termsAndConditions->update($request->validated());

        return $this->createdResponse(
            message: translate_success_message('terms', 'updated')
        );
    }
}
