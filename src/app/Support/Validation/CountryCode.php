<?php

declare(strict_types=1);

namespace App\Support\Validation;

use Illuminate\Contracts\Validation\Rule;
use League\ISO3166\Exception\DomainException;
use League\ISO3166\Exception\OutOfBoundsException;
use League\ISO3166\ISO3166;

class CountryCode implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            (new ISO3166)->alpha2($value);

            return true;
        } catch (DomainException | OutOfBoundsException $e) {
            return false;
        }
    }

    public function message(): string
    {
        return 'The :attribute must be a valid ISO3166-A2 code.';
    }
}
