<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum FileModeEnum: string
{
    case ADD = 'add';
    case OVERLAY = 'overlay';
}
