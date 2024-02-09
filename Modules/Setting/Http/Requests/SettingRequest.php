<?php

namespace Modules\Setting\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SettingRequest extends FormRequest
{
    use HttpResponse;

    public function rules(): array
    {
        //        addTranslationRules($rules);
        return [
            'title' => ValidationRuleHelper::stringRules(),
            'address' => ValidationRuleHelper::addressRules(),
            'phone' => ValidationRuleHelper::phoneRules(),
            'email' => ValidationRuleHelper::urlRules(false),
            'twitter' => ValidationRuleHelper::urlRules(false),
            'instagram' => ValidationRuleHelper::urlRules(false),
            'linkedin' => ValidationRuleHelper::urlRules(false),
            'facebook' => ValidationRuleHelper::urlRules(false),
            'youtube' => ValidationRuleHelper::urlRules(false),
            'whatsapp' => ValidationRuleHelper::phoneRules(['required' => '']),
            'working_hours' => ValidationRuleHelper::stringRules(),
        ];
    }

    /**
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
