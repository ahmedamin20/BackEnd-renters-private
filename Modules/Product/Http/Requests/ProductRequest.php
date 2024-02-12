<?php

namespace Modules\Product\Http\Requests;

use App\Helpers\ValidationMessageHelper;
use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Modules\GeneralConfig;

class ProductRequest extends FormRequest
{
    use HttpResponse;

    private bool $inUpdate;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->inUpdate = ! preg_match('/.*products$/', request()->url());
        $this->stopOnFirstFailure = GeneralConfig::stopOnFirstFailure();
    }

    public function prepareForValidation()
    {
        $inputs = $this->all();

        $this->sanitizeStringToArray('features', $inputs);
        $this->sanitizeStringToArray('stored_images', $inputs);
        $this->sanitizeStringToArray('kept_images', $inputs);

        if (isset($inputs['stored_images'])) {
            for ($i = 0; $i < count($inputs['stored_images']); $i++) {
                $inputs['stored_images'][$i] = trim(Str::replace('/', '', $inputs['stored_images'][$i]), '.');
            }
        }

        if (! $this->hasFile('main_image')) {
            unset($inputs['main_image']);
        }
        $this->replace($inputs);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ValidationRuleHelper::stringRules(),
            'category_id' => [
                'required',
            ],
            'price' => ValidationRuleHelper::doubleRules(),
            'description' => ValidationRuleHelper::longTextRules([
                'required' => 'nullable',
                'string' => '',
            ]),
            'main_image' => ValidationRuleHelper::storeOrUpdateImageRules($this->inUpdate),
            'other_images' => ValidationRuleHelper::arrayRules(['required' => 'sometimes']),
            'other_images.*' => ValidationRuleHelper::storeOrUpdateImageRules(true),
            'minimum_days' => ValidationRuleHelper::integerRules(['required' => 'nullable', ]),
            'maximum_days' => ValidationRuleHelper::integerRules(['required' => 'nullable', 'gt' => 'gt:minimum_days']),
            'health' => ValidationRuleHelper::integerRules(['required' => 'nullable']),
            'deleted_images' => ValidationRuleHelper::arrayRules([
                'required' => 'sometimes',
            ]),
            'deleted_images.*' => ValidationRuleHelper::foreignKeyRules([
                'required' => 'sometimes',
            ]),
        ];
    }

    public function messages(): array
    {
        return array_merge(
            ValidationMessageHelper::stringErrorMessages(),
            ValidationMessageHelper::integerErrorMessages('quantity', ['required' => 'required']),
            ValidationMessageHelper::doubleErrorMessages('price'),
            ValidationMessageHelper::imageErrorMessages('main_image'),
            ValidationMessageHelper::longTextErrorMessages('description'),
            ['category_id.required' => translate_error_message('category', 'required')],
        );
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }

    protected function sanitizeStringToArray(string $keyName, array &$inputs)
    {
        if (isset($inputs[$keyName]) && is_string($inputs[$keyName])) {
            $inputs[$keyName] = json_decode($inputs[$keyName], true);
        }
    }
}
