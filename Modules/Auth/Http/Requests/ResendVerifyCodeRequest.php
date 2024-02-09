<?php

namespace Modules\Auth\Http\Requests;

use App\Rules\KeyExists;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ResendVerifyCodeRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'handle' => new KeyExists(),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
