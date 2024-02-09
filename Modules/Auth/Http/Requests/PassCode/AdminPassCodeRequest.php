<?php

namespace Modules\Auth\Http\Requests\PassCode;

use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AdminPassCodeRequest extends FormRequest
{
    use HttpResponse;

    public function rules(): array
    {
        return [
            'user_id' => ValidationRuleHelper::foreignKeyRules(),
            'new_pass_code' => ValidationRuleHelper::passCodeRules(),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
