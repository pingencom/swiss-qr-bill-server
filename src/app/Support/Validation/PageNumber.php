<?php

declare(strict_types=1);

namespace App\Support\Validation;

use App\Support\PdfHelper;
use Illuminate\Contracts\Validation\Rule;

class PageNumber implements Rule
{
    public function __construct(public string $pdfPath)
    {
    }

    public function passes($attribute, $value): bool
    {
        if($value > $this->getPdfPages()) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'Page to overlay does not exists.';
    }

    private function getPdfPages(): int
    {
        /** @var PdfHelper $pdfHelper */
        $pdfHelper = app()->make(PdfHelper::class);

        return $pdfHelper->countPdfPages($this->pdfPath);
    }
}
