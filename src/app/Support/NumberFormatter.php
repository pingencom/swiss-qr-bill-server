<?php

declare(strict_types=1);

namespace App\Support;

class NumberFormatter
{
    public static function format(float $number): string
    {
        return number_format(
            $number,
            2,
            '.',
            ' '
        );
    }
}
