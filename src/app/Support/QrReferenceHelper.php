<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

class QrReferenceHelper
{
    public function generateQrReference(string $reference): string
    {
        $qrReference = (string)Str::of($reference)->padLeft(26, '0');

        return $qrReference . $this->generateModulo10($qrReference);
    }

    public function formatQrReference(string $reference): string
    {
        $newReference = strrev($reference);

        $newReference = trim(chunk_split($newReference, 5, ' '));

        return strrev($newReference);
    }

    private function generateModulo10(string $qrReference): int
    {
        $table = [0, 9, 4, 6, 8, 2, 7, 1, 3, 5];
        $next = 0;
        for ($i = 0; $i < strlen($qrReference); $i++) {
            $next = $table[($next + (int)substr($qrReference, $i, 1)) % 10];
        }

        return (10 - $next) % 10;
    }
}
