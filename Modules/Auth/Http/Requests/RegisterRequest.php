<?php

namespace Modules\Auth\Http\Requests;

use App\Helpers\ValidationMessageHelper;
use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Facades\Captcha;
use Modules\Auth\Facades\IsEnabled;

class RegisterRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            AuthEnum::UNIQUE_COLUMN => AuthEnum::isUniqueKeyEmail()
                ? ValidationRuleHelper::emailRules()
                : ValidationRuleHelper::phoneRules(),
            'phone' => ValidationRuleHelper::phoneRules([
                'required' => 'nullable',
            ]),
            'front_national_id' => ValidationRuleHelper::storeOrUpdateImageRules(),
            'back_national_id' => ValidationRuleHelper::storeOrUpdateImageRules(),
            'name' => ValidationRuleHelper::stringRules(),
            'password' => ValidationRuleHelper::defaultPasswordRules(),
        ]
            + (
                IsEnabled::captcha()
                    ? Captcha::getCaptchaValidationRules()
                    : []
            );
    }

    public function messages(): array
    {
        return array_merge(
            ValidationMessageHelper::addressErrorMessages(),
            ValidationMessageHelper::stringErrorMessages(),
            ValidationMessageHelper::passwordErrorMessages(),
            ValidationMessageHelper::roleErrorMessages(),
            ValidationMessageHelper::imageErrorMessages(),
            ValidationMessageHelper::latitudeErrorMessages(),
            ValidationMessageHelper::longitudeErrorMessages(),
            (
                IsEnabled::captcha()
                    ? Captcha::getCaptchaErrorMessages()
                    : []
            )
        );
    }

    /**
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
