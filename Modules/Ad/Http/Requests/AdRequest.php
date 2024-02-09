<?php

namespace Modules\Ad\Http\Requests;

use App\Helpers\ValidationMessageHelper;
use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\GeneralConfig;

class AdRequest extends FormRequest
{
    use HttpResponse;

    private bool $inUpdate;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->inUpdate = ! preg_match('/.*ads$/', request()->url());
        $this->stopOnFirstFailure = GeneralConfig::stopOnFirstFailure();
    }

    public function prepareForValidation()
    {
        $inputs = $this->all();

        if (! $this->hasFile('image')) {
            unset($inputs['image']);
        }

        $inputs['discount'] = \Str::replace('%', '', $this->input('discount'));

        $this->replace($inputs);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ValidationRuleHelper::stringRules(),
            'description' => ValidationRuleHelper::longTextRules(),
            'discount' => ValidationRuleHelper::integerRules(['sometimes' => '', 'required' => 'required', 'max' => 'max:100']),
            'image' => ValidationRuleHelper::storeOrUpdateImageRules($this->inUpdate),
        ];
    }

    public function messages(): array
    {
        return array_merge(
            ValidationMessageHelper::stringErrorMessages('title'),
            ValidationMessageHelper::integerErrorMessages('discount'),
            ValidationMessageHelper::imageErrorMessages('image')
        );
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
