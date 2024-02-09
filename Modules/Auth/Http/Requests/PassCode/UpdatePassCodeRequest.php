<?php

namespace Modules\Auth\Http\Requests\PassCode;

use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassCodeRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'current_pass_code' => ValidationRuleHelper::passCodeRules(),
            'new_pass_code' => ValidationRuleHelper::passCodeRules(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
