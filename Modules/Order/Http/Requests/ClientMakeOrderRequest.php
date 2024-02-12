<?php

namespace Modules\Order\Http\Requests;

use App\Helpers\ValidationMessageHelper;
use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ClientMakeOrderRequest extends FormRequest
{
    use HttpResponse;

    public function prepareForValidation()
    {
        $inputs = $this->all();

        if (isset($inputs['features']) && is_string($inputs['features'])) {
            $inputs['features'] = json_decode($inputs['features'], true);
        }

        $this->replace($inputs);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'features' => ValidationRuleHelper::arrayRules(),
            'features.*' => ValidationRuleHelper::foreignKeyRules(),
            'product_id' => ValidationRuleHelper::foreignKeyRules(),
            'name' => ValidationRuleHelper::stringRules(),
            'phone' => ValidationRuleHelper::phoneRules(['unique' => '']),
            'address' => ValidationRuleHelper::addressRules(),
            'coupon' => ValidationRuleHelper::stringRules(['required' => '']),
            'quantity' => ValidationRuleHelper::integerRules(['sometimes' => '', 'required' => 'required']),
        ];
    }

    public function messages(): array
    {
        return array_merge(
            ValidationMessageHelper::stringErrorMessages(),
            ValidationMessageHelper::stringErrorMessages('coupon'),
            ValidationMessageHelper::phoneErrorMessages(),
            ValidationMessageHelper::longTextErrorMessages('address')
        );
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
