<?php

namespace Modules\Auth\Http\Requests;

use App\Helpers\ValidationMessageHelper;
use App\Helpers\ValidationRuleHelper;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Enums\AuthEnum;
use Modules\GeneralConfig;

class ProfileRequest extends FormRequest
{
    use HttpResponse;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->stopOnFirstFailure = GeneralConfig::stopOnFirstFailure();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $uniqueColumn = AuthEnum::UNIQUE_COLUMN;

        return [
            'name' => ValidationRuleHelper::stringRules(),
            $uniqueColumn => ValidationRuleHelper::emailRules([
                'unique' => ValidationRuleHelper::getUniqueColumn(
                    true,
                    (new User())->getTable(),
                    auth()->id(),
                ),
                'email' => 'email',
            ]),
            'avatar' => ValidationRuleHelper::storeOrUpdateImageRules(true),
            'address' => ValidationRuleHelper::longTextRules([
                'required' => 'sometimes'
            ]),
        ];
    }

    public function messages(): array
    {
        return array_merge(
            ValidationMessageHelper::stringErrorMessages(),
            ValidationMessageHelper::phoneErrorMessages(),
            ValidationMessageHelper::imageErrorMessages(),
            ValidationMessageHelper::addressErrorMessages(),
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
