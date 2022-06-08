<?php

declare(strict_types=1);

namespace App\Support;

use App\Support\DataTransferObjects\QrBillDataDTO;
use App\Support\Enum\QRTypeEnum;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class QrCodeHelper
{
    public function __construct(public FileHelper $fileHelpers)
    {
    }

    public function generateQrCode(QrBillDataDTO $qrBillDataDTO, string $qrReference): string
    {
        $qrWithoutLogo = $this->fileHelpers->tempnam('/tmp') . '.png';
        $qrWithLogo = $this->fileHelpers->tempnam('/tmp') . '.png';

        $qrLines = [];

        $qrLines[] = 'SPC';
        $qrLines[] = '0200';
        $qrLines[] = '1';
        $qrLines[] = $qrBillDataDTO->iban;
        $qrLines[] = 'K';
        $qrLines[] = $qrBillDataDTO->creditor_name;
        $qrLines[] = sprintf('%s %s', $qrBillDataDTO->creditor_street, $qrBillDataDTO->creditor_street_number);
        $qrLines[] = sprintf('%s %s', $qrBillDataDTO->creditor_post_code, $qrBillDataDTO->creditor_city);
        $qrLines[] = '';
        $qrLines[] = '';
        $qrLines[] = $qrBillDataDTO->creditor_country;
        $qrLines[] = '';
        $qrLines[] = '';
        $qrLines[] = '';
        $qrLines[] = '';
        $qrLines[] = '';
        $qrLines[] = '';
        $qrLines[] = '';
        $qrLines[] = $qrBillDataDTO->total_amount;
        $qrLines[] = $qrBillDataDTO->currency->value;
        $qrLines[] = 'S';
        $qrLines[] = $qrBillDataDTO->debitor_name;
        $qrLines[] = $qrBillDataDTO->debitor_street;
        $qrLines[] = $qrBillDataDTO->debitor_street_number;
        $qrLines[] = $qrBillDataDTO->debitor_post_code;
        $qrLines[] = $qrBillDataDTO->debitor_city;
        $qrLines[] = $qrBillDataDTO->debitor_country;
        $qrLines[] = $qrBillDataDTO->type->value;
        $qrLines[] = $qrBillDataDTO->type === QRTypeEnum::WITH_REFERENCE ? $qrReference : '';
        $qrLines[] = Str::of($qrBillDataDTO->additional_information)->substr(0, 140);
        $qrLines[] = 'EPD';

        $inputString = implode(PHP_EOL, $qrLines);

        $process = Process::fromShellCommandline('qrencode -l M -t PNG -o ' . $qrWithoutLogo . ' "' . $inputString . '" && composite -gravity center ' . resource_path('images/logo_cross.png') . ' ' . $qrWithoutLogo . ' ' . $qrWithLogo);
        $process->mustRun();

        /** @var string $fileContent */
        $fileContent = file_get_contents($qrWithLogo);

        $this->fileHelpers->destroyFile($qrWithoutLogo);
        $this->fileHelpers->destroyFile($qrWithLogo);

        return base64_encode($fileContent);
    }
}
