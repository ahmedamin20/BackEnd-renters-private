<?php

namespace Modules\Order\Http\Requests;

use App\Helpers\DateHelper;
use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApproveOrderRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'delivery_man_id' => ['required', 'numeric'],
            'delivers_at' => ValidationRuleHelper::dateRules([
                'date_format' => 'date_format:'.DateHelper::defaultDateFormat(),
                'after_or_equal' => 'after_or_equal:'.now()->format(DateHelper::defaultDateFormat()),
            ]),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
