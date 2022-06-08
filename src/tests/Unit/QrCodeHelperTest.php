<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\DataTransferObjects\QrBillDataDTO;
use App\Support\Enum\CurrencyEnum;
use App\Support\Enum\LanguageEnum;
use App\Support\Enum\QRTypeEnum;
use App\Support\FileHelper;
use App\Support\PdfHelper;
use App\Support\QrCodeHelper;
use App\Support\QrReferenceHelper;
use Tests\TestCase;

class QrCodeHelperTest extends TestCase
{
    public function testPositive(): void
    {
        /** @var QrCodeHelper $qrCodeHelper */
        $qrCodeHelper = app()->make(QrCodeHelper::class);
        /** @var QrReferenceHelper $qrReferenceHelper */
        $qrReferenceHelper = app()->make(QrReferenceHelper::class);

        $refNumber = '12345678901234567890123456';

        $qrBillDataDTO = new QrBillDataDTO([
            'iban' => 'CH5604835012345678009',
            'creditor_name' => 'Test Name Creditor',
            'creditor_street' => 'Test Street Creditor',
            'creditor_street_number' => '70',
            'creditor_post_code' => '8005',
            'creditor_city' => 'Zürich',
            'creditor_country' => 'CH',
            'total_amount' => '100.12',
            'currency' => CurrencyEnum::CHF,
            'debitor_name' => 'Test Name Debitor',
            'debitor_street' => 'Test Street Debitor',
            'debitor_street_number' => '70',
            'debitor_post_code' => '8005',
            'debitor_city' => 'Zürich',
            'debitor_country' => 'CH',
            'language' => LanguageEnum::EN,
            'type' => QRTypeEnum::WITH_REFERENCE,
            'reference' => $refNumber,
            'additional_information' => 'TestPaymentId123'
        ]);

        $this->assertIsString($qrCodeHelper->generateQrCode($qrBillDataDTO, $qrReferenceHelper->generateQrReference($refNumber)));
    }

    public function testNegative(): void
    {
        $this->mock(FileHelper::class)
            ->shouldReceive('tempnam')
            ->andThrow(new \RuntimeException('Failed creating tmp name.'));

        $this->expectException(\RuntimeException::class);

        $qrCodeHelper = app()->make(QrCodeHelper::class);

        $refNumber = '12345678901234567890123456';

        $qrBillDataDTO = new QrBillDataDTO([
            'iban' => 'CH5604835012345678009',
            'creditor_name' => 'Test Name Creditor',
            'creditor_street' => 'Test Street Creditor',
            'creditor_street_number' => '70',
            'creditor_post_code' => '8005',
            'creditor_city' => 'Zürich',
            'creditor_country' => 'CH',
            'total_amount' => '100.12',
            'currency' => CurrencyEnum::CHF,
            'debitor_name' => 'Test Name Debitor',
            'debitor_street' => 'Test Street Debitor',
            'debitor_street_number' => '70',
            'debitor_post_code' => '8005',
            'debitor_city' => 'Zürich',
            'debitor_country' => 'CH',
            'language' => LanguageEnum::EN,
            'type' => QRTypeEnum::WITH_REFERENCE,
            'reference' => $refNumber,
            'additional_information' => 'TestPaymentId123'
        ]);

        $qrCodeHelper->generateQrCode($qrBillDataDTO, 'ref');
    }

    public function testDestroyFilePositive(): void
    {
        /** @var FileHelper $fileHelper */
        $fileHelper = app()->make(FileHelper::class);

        $tmpFile = $fileHelper->tempnam('/tmp') . '.pdf';
        file_put_contents($tmpFile, 'test');

        $this->assertTrue(file_exists($tmpFile));
        $this->assertEquals('test', file_get_contents($tmpFile));

        $fileHelper->destroyFile($tmpFile);

        $this->assertFalse(file_exists($tmpFile));
    }
}
