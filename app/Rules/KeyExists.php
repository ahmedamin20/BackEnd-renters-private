<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KeyExists implements ValidationRule
{
    public bool $implicit = true;

    private string $table;

    private string $column;

    public function __construct(string $table = 'users', string $column = 'email')
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //TODO Check If The Key Exists
        if (! $value) {
            $fail(translate_error_message('handle', 'required'));
        }
        $exists = \DB::table($this->table)
            ->where([$this->column => $value])
            ->first(['id']);
        if (! $exists) {
            $fail(translate_error_message('handle', 'not_exists'));
        }

    }
}
