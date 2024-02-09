<?php

namespace Modules\Role\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ValidationRuleHelper::stringRules(),
            'permissions' => ValidationRuleHelper::arrayRules(),
            'permissions.*' => ValidationRuleHelper::foreignKeyRules(['distinct' => 'distinct']),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
