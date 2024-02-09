<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Facades\IsEnabled;
use Modules\GeneralConfig;

class ForgotPasswordRequest extends FormRequest
{
    use HttpResponse;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->stopOnFirstFailure = GeneralConfig::stopOnFirstFailure();
    }

    public function authorize(): bool
    {
        return IsEnabled::forgotPassword();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => translate_error_message('email', 'required'),
            'email.email' => translate_error_message('email', 'email'),
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
