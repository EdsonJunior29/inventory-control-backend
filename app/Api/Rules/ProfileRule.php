<?php

namespace App\Api\Rules;

use App\Models\Profile;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->findRule($value)) {
            $fail($this->message());
        }
    }

    private function findRule($value) : bool
    {
        return Profile::where('name', $value)->exists();
    }


    public function message()
    {
        return 'The specified profile name does not exist.';
    }
}