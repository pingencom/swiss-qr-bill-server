<?php

declare(strict_types=1);

namespace App\Support\Validation;

use Illuminate\Contracts\Validation\Rule;

class QRIban implements Rule
{
    private const MIN = 30000;
    private const MAX = 31999;

    public function __construct(public string $type)
    {
    }

    public function passes($attribute, $value): bool
    {
        if ($this->type === 'QRR') {
            return $this->hasValidStructure($value);
        }

        return true;
    }

    public function message(): string
    {
        return 'The :attribute it is not valid QR-IBAN.';
    }

    private function hasValidStructure(string $iban): bool
    {
        $digits = (int)substr($iban, 4, 5);

        return $digits >= self::MIN && $digits <= self::MAX;
    }
}
