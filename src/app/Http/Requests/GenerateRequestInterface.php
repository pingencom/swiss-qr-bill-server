<?php

declare(strict_types=1);

namespace App\Http\Requests;

interface GenerateRequestInterface
{
    public function rules(): array;
}
