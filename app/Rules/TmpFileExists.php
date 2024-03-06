<?php

namespace App\Rules;

use App\Http\Controllers\FileManagerController;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Str;

class TmpFileExists implements ValidationRule
{
    public bool $implicit = true;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = trim(Str::replace('/', '', $value), '.');

        if (! file_exists(storage_path('app/public/tmp/'.FileManagerController::getSubTmpDirectoryName().'/'.$value))) {

            $fail(translate_error_message('file', 'not_found'));
        }
    }
}
