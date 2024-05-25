<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidatesMinimumNumberOfCharacters($value) ||
            !$this->containsUppercaseLetter($value) ||
            !$this->containsLowercaseLetter($value) ||
            !$this->containsNumber($value) ||
            !$this->containsSpecialCharacter($value) ||
            $this->containsSequentialNumber($value)) {
            $fail($this->message());
        }
    }

    private function isValidatesMinimumNumberOfCharacters($value)
    {
        return strlen($value) >= 8;
    }

    private function containsUppercaseLetter($value)
    {
        return preg_match('/[A-Z]/', $value);
    }

    private function containsLowercaseLetter($value)
    {
        return preg_match('/[a-z]/', $value);
    }

    private function containsNumber($value)
    {
        return preg_match('/[0-9]/', $value);
    }

    private function containsSpecialCharacter($value)
    {
        return preg_match('/[a-zA-Z0-9_]/', $value);
    }

    private function containsSequentialNumber($value)
    {
        return preg_match('/123|234|345|456|567|678|789/', $value);
    }

    public function message()
    {
        $informationText = 'The : attribute must be at least8 characters long, contain at least on uppercase letter,';
        $informationText .= ' on lowercase letter, one number, one special character, and no sequencial number.';
        return $informationText;
    }
}
