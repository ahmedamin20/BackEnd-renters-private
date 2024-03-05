<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationRuleHelper;
use Elattar\Prepare\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;

class OrderRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        return [
            'product_id' => ValidationRuleHelper::foreignKeyRules(),
            'from_date' => ValidationRuleHelper::dateRules(),
            'to_date' => ValidationRuleHelper::dateRules([
                'after' => 'after:from_date'
            ]),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
