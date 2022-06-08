<?php

declare(strict_types=1);

namespace App\Support\Validation;

use Illuminate\Contracts\Validation\Rule;

class Iban implements Rule
{
    private const IBAN_LENGTHS = [
        'CH' => 21,
        'LI' => 21
    ];

    public function passes($attribute, $value): bool
    {
        $value = str_replace(' ', '', strtoupper($value));

        return $this->hasValidLength($value) && $this->getChecksum($value) === 1;
    }

    public function message(): string
    {
        return 'The :attribute must be a correct CH iban.';
    }

    private function getChecksum(string $iban): int
    {
        $iban = substr($iban, 4) . substr($iban, 0, 4);
        $iban = str_replace(range('A', 'Z'), range(10, 35), $iban);
        $checksum = 0;

        foreach (str_split($iban) as $key => $character) {
            $checksum *= 10;
            $checksum += intval(substr($iban, $key, 1));
            $checksum %= 97;
        }

        return $checksum;
    }

    private function hasValidLength(string $iban): bool
    {
        return (self::IBAN_LENGTHS[substr($iban, 0, 2)] ?? 0) === strlen($iban);
    }
}
