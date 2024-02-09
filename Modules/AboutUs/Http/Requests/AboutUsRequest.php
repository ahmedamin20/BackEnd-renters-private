<?php

namespace Modules\AboutUs\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    use HttpResponse;

    public function prepareForValidation()
    {
        $inputs = $this->all();

        if (! $this->hasFile('image')) {
            unset($inputs['image']);
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
            'youtube_video_url' => ValidationRuleHelper::urlRules(),
            'description' => ValidationRuleHelper::longTextRules(),
            'image' => ValidationRuleHelper::storeOrUpdateImageRules(true),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
