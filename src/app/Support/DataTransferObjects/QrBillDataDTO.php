<?php

declare(strict_types=1);

namespace App\Support\DataTransferObjects;

use App\Support\Enum\CurrencyEnum;
use App\Support\Enum\LanguageEnum;
use App\Support\Enum\FileModeEnum;
use App\Support\Enum\QRTypeEnum;
use Spatie\DataTransferObject\DataTransferObject;

class QrBillDataDTO extends DataTransferObject
{
    public string $iban;
    public string $creditor_name;
    public string $creditor_street;
    public string $creditor_street_number;
    public string $creditor_post_code;
    public string $creditor_city;
    public string $creditor_country;
    public float $total_amount;
    public CurrencyEnum $currency;
    public string $debitor_name;
    public string $debitor_street;
    public string $debitor_street_number;
    public string $debitor_post_code;
    public string $debitor_city;
    public string $debitor_country;
    public ?string $additional_information;
    public LanguageEnum $language;
    public QRTypeEnum $type;
    public ?string $reference;
    public ?string $file_path;
    public ?FileModeEnum $file_mode;
    public ?int $file_overlay_page;
}
