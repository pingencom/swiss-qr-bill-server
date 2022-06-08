<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum QRTypeEnum: string
{
    case WITH_REFERENCE = 'QRR';
    case WITHOUT_REFERENCE = 'NON';
}
