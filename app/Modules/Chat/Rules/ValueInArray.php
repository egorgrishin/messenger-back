<?php

namespace App\Modules\Chat\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValueInArray implements ValidationRule
{
    public function __construct(
        private readonly mixed $value,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($this->value, $value)) {
            $fail("Element $this->value must be represented in the array.");
        }
    }
}
