<?php

namespace Modules\Auth\Http\Controllers;

use Closure;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class CaptchaController extends Controller
{
    /**
     * Get CaptchaFacade Rules If Enabled
     */
    public function getCaptchaValidationRules(): array
    {
        $rules = [];
        $rules['g-recaptcha-response'] = [
            'required',
            function (string $attribute, mixed $value, Closure $fail) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',
                    [
                        'response' => $value,
                        'secret' => config('services.recaptcha.RECAPTCHA_SECRET_KEY'),
                        'remoteip' => request()->ip(),
                    ]);
                if (! $response->json('success')) {
                    $fail(translate_error_message('captcha', 'wrong'));
                }
            },
        ];

        return $rules;
    }

    public function getCaptchaErrorMessages(): array
    {
        $messages = [];
        $messages['g-recaptcha-response.required'] = translate_error_message('captcha', 'required');

        return $messages;
    }
}
