<?php

declare(strict_types=1);

namespace App\Support;

class FileHelper
{
    public function tempnam(string $dir): string
    {
        $tempName = \tempnam($dir, 'qr');

        if (true !== is_string($tempName)) {
            throw new \RuntimeException('Failed creating tmp name.'); //@codeCoverageIgnore
        }

        return $tempName;
    }

    public function destroyFile(string $filePath): void
    {
        unlink($filePath);
    }
}
