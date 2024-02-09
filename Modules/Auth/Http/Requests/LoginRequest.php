<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Facades\Captcha;
use Modules\Auth\Facades\IsEnabled;
use Modules\GeneralConfig;

class LoginRequest extends FormRequest
{
    use HttpResponse;

    /**
     * @var bool
     */
    protected $stopOnFirstFailure = false;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->stopOnFirstFailure = GeneralConfig::stopOnFirstFailure();
    }

    public function authorize(): bool
    {
        return IsEnabled::spaLogin() || IsEnabled::mobileLogin();
    }

    public function prepareForValidation(): void
    {
        $inputs = $this->all();

        if (! isset($inputs['remember_me'])) {
            unset($inputs['remember_me']);
        }

        $this->replace($inputs);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            AuthEnum::UNIQUE_COLUMN => ['required'],
            'password' => ['required'],
            'remember_me' => ['sometimes', 'boolean'],
            'fcm_token' => [
                'required',
                'string',
            ],
        ] + (
            IsEnabled::captcha()
                ? Captcha::getCaptchaValidationRules()
                : []
        );
    }

    public function messages(): array
    {
        return [
            'email.required' => translate_error_message('email', 'required'),
            'password.required' => translate_error_message('password', 'required'),
            'remember_me.boolean' => translate_error_message('remember_me', 'boolean'),
        ] + (
            IsEnabled::captcha()
                ? Captcha::getCaptchaErrorMessages()
                : []
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
