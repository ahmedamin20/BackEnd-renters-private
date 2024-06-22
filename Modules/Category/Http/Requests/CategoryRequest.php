<?php

namespace Modules\Category\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        $inUpdate = ! preg_match('/.*categories$/', $this->url());
        $idValue = $this->route('id');

        return [
            'name' => ValidationRuleHelper::stringRules([
                'unique' => ValidationRuleHelper::getUniqueColumn($inUpdate, 'categories', $idValue, 'name'),
            ]),
            'image' => ValidationRuleHelper::storeOrUpdateImageRules($inUpdate),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
