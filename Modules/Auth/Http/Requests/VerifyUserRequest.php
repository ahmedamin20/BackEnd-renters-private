<?php

namespace Modules\Auth\Http\Requests;

use App\Rules\KeyExists;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class VerifyUserRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'handle' => new KeyExists(),
            'code' => ['required', 'numeric'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
