<?php

namespace Modules\Auth\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Enums\SocialAuthEnum;

class SocialAuthRequest extends FormRequest
{
    use HttpResponse;

    public function rules()
    {
        $provider = $this->route('provider') ?? null;

        $rules = [
            'access_token' => ['required'],
            'secret' => ['sometimes'],
            'provider' => ValidationRuleHelper::enumRules(SocialAuthEnum::availableProviders()),
        ];

        if (! $provider == SocialAuthEnum::TWITTER) {
            unset($rules['secret']);
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
