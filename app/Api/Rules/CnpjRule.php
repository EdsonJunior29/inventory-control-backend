<?php

namespace App\Api\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidCNPJ($value)) {
            $fail('The :attribute is not a valid CNPJ.');
        }
    }

    private function isValidCNPJ(string $value)
    {
        // Remove non-numeric characters
        $cnpj = preg_replace('/\D/', '', $value);

        // Check if the CNPJ has 14 digits
        if (strlen($cnpj) !== 14) {
            return false;
        }

        // CNPJ não pode ser uma sequência de números repetidos
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        // Validate using the algorithm for the two verification digits
        // Multipliers for the first digit
        $multipliers1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        // Multipliers for the second digit
        $multipliers2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        // Calculate the first verification digit
        $sum1 = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum1 += (int)$cnpj[$i] * $multipliers1[$i];
        }
        $digit1 = ($sum1 % 11 < 2) ? 0 : (11 - $sum1 % 11);

        // Calculate the second verification digit
        $sum2 = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum2 += (int)$cnpj[$i] * $multipliers2[$i];
        }
        $digit2 = ($sum2 % 11 < 2) ? 0 : (11 - $sum2 % 11);

        // Check if the calculated digits match the CNPJ digits
        return ($digit1 == (int)$cnpj[12] && $digit2 == (int)$cnpj[13]);
    }
}