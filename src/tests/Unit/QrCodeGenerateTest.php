<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\Enum\CurrencyEnum;
use App\Support\Enum\LanguageEnum;
use App\Support\Enum\FileModeEnum;
use App\Support\Enum\QRTypeEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class QrCodeGenerateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->travelTo(Carbon::make('2012-01-01'));
    }

    /**
     * @dataProvider positiveCaseProvider
     */
    public function testPositive(array $body, string $expectedFile, string $expectedFileName): void
    {
        $response = $this->post('api/generate', $body);

        $response->assertDownload($expectedFileName);
        $this->assertPdfTextEquals($expectedFile, $response->streamedContent());
    }

    /**
     * @dataProvider missingAttributeProvider
     */
    public function testMissingRequiredAttribute(array $body, array $expectedError, string $errorType): void
    {
        $response = $this->post('api/generate', $body);

        $response = json_decode($response->getContent());

        $this->assertEquals('Validation failed', $response->title);

        foreach ($response->message as $type => $errorMessages) {
            $this->assertEquals($errorType, $type);

            foreach ($errorMessages as $key => $msg) {
                $this->assertEquals($expectedError[$key], $msg);
            }
        }
    }

    protected function positiveCaseProvider(): array
    {
        $file = UploadedFile::fake()->createWithContent('pdfTest.pdf', file_get_contents('storage/test/test.pdf'));
        $this->travelTo(Carbon::make('2012-01-01'));

        return [
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                'storage/test/qrCode.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100',
                    'currency' => CurrencyEnum::EUR->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::DE->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                'storage/test/qrCodeDe.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::IT->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                'storage/test/qrCodeIt.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::FR->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                'storage/test/qrCodeFr.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '1012',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                'storage/test/qrCodeWithoutPaymentReference.txt',
                'QR-Bill_CH5604835012345678009_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => $file,
                    'file_mode' => FileModeEnum::ADD->value
                ],
                'storage/test/qrCodeWithPage.txt',
                'QR-Bill_CH5604835012345678009_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => $file,
                    'file_mode' => FileModeEnum::OVERLAY->value,
                    'file_overlay_page' => '1'
                ],
                'storage/test/qrCodeWithOverlayInFirstPage.txt',
                'QR-Bill_CH5604835012345678009_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => $file,
                    'file_mode' => FileModeEnum::OVERLAY->value,
                    'file_overlay_page' => '2'
                ],
                'storage/test/qrCodeWithOverlayInSecondPage.txt',
                'QR-Bill_CH5604835012345678009_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123',
                    'file' => $file,
                    'file_mode' => FileModeEnum::OVERLAY->value,
                    'file_overlay_page' => '3'
                ],
                'storage/test/qrCodeWithOverlayInThirdPage.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123',
                    'file' => $file,
                    'file_mode' => FileModeEnum::OVERLAY->value,
                    'file_overlay_page' => '4'
                ],
                'storage/test/qrCodeWithOverlayInLastPage.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123',
                    'file' => UploadedFile::fake()->createWithContent('onePageTest.pdf', file_get_contents('storage/test/onePageTest.pdf')),
                    'file_mode' => FileModeEnum::OVERLAY->value,
                    'file_overlay_page' => '1'
                ],
                'storage/test/qrCodeWithOverlayInOnePageDocument.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123',
                    'file' => UploadedFile::fake()->createWithContent('onePageTest.pdf', file_get_contents('storage/test/twoPageTest.pdf')),
                    'file_mode' => FileModeEnum::OVERLAY->value,
                    'file_overlay_page' => '2'
                ],
                'storage/test/qrCodeWithOverlayInTwoPageDocument.txt',
                'QR-Bill_CH4431999123000889012_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ]
        ];
    }

    protected function missingAttributeProvider(): array
    {
        return [
            [
                [
                    'iban' => 'wrongIban',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The iban must be 21 characters.',
                    1 => 'The iban must be a correct CH iban.'
                ],
                'iban'
            ],
            [
                [
                    'iban' => 'PL4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The iban must be a correct CH iban.'
                ],
                'iban'
            ],
            [
                [
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The iban field is required.'
                ],
                'iban'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The iban it is not valid QR-IBAN.'
                ],
                'iban'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor name field is required.'
                ],
                'creditor_name'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => Str::random(71),
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor name must not be greater than 70 characters.'
                ],
                'creditor_name'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor street field is required.'
                ],
                'creditor_street'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => Str::random(71),
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor street must not be greater than 70 characters.'
                ],
                'creditor_street'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor street number field is required.'
                ],
                'creditor_street_number'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => Str::random(71),
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor street number must not be greater than 70 characters.'
                ],
                'creditor_street_number'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => 'StreetNumber',
                    'creditor_post_code' =>  Str::random(17),
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor post code must not be greater than 16 characters.'
                ],
                'creditor_post_code'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => 'StreetNumber',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor post code field is required.'
                ],
                'creditor_post_code'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '90',
                    'creditor_post_code' => '8005',
                    'creditor_city' => Str::random(36),
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor city must not be greater than 35 characters.'
                ],
                'creditor_city'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '90',
                    'creditor_post_code' => '8005',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor city field is required.'
                ],
                'creditor_city'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor country field is required.'
                ],
                'creditor_country'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'WrongCountry',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor country must not be greater than 2 characters.',
                    1 => 'The creditor country must be a valid ISO3166-A2 code.'
                ],
                'creditor_country'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'XX',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The creditor country must be a valid ISO3166-A2 code.'
                ],
                'creditor_country'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100,12',
                    'currency' => 'EUR',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The total amount format is invalid.'
                ],
                'total_amount'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'currency' => 'EUR',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The total amount field is required.'
                ],
                'total_amount'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => Str::random(71),
                    'currency' => 'EUR',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The total amount must not be greater than 70 characters.',
                    1 => 'The total amount format is invalid.'
                ],
                'total_amount'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => 'PLN',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The selected currency is invalid.'
                ],
                'currency'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => 'PLND',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The currency must not be greater than 3 characters.',
                    1 => 'The selected currency is invalid.'
                ],
                'currency'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The currency field is required.'
                ],
                'currency'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor name field is required.'
                ],
                'debitor_name'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => Str::random(71),
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor name must not be greater than 70 characters.'
                ],
                'debitor_name'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor street field is required.'
                ],
                'debitor_street'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => '90',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => Str::random(71),
                    'debitor_street_number' => '90',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor street must not be greater than 70 characters.'
                ],
                'debitor_street'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor street number field is required.'
                ],
                'debitor_street_number'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '90',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => Str::random(71),
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor street number must not be greater than 70 characters.'
                ],
                'debitor_street_number'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => 'StreetNumber',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => Str::random(17),
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor post code must not be greater than 16 characters.'
                ],
                'debitor_post_code'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => 'StreetNumber',
                    'creditor_city' => 'Zürich',
                    'creditor_post_code' => '8005',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor post code field is required.'
                ],
                'debitor_post_code'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '90',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => Str::random(36),
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor city must not be greater than 35 characters.'
                ],
                'debitor_city'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '90',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor city field is required.'
                ],
                'debitor_city'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor country field is required.'
                ],
                'debitor_country'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'WrongCountry',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor country must not be greater than 2 characters.',
                    1 => 'The debitor country must be a valid ISO3166-A2 code.'
                ],
                'debitor_country'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'XX',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The debitor country must be a valid ISO3166-A2 code.'
                ],
                'debitor_country'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => 'wrongType',
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The selected type is invalid.'
                ],
                'type'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => 'Wrong',
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The reference must be between 1 and 26 digits.'
                ],
                'reference'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The reference field is required when type is QRR.'
                ],
                'reference'
            ],
            [
                [
                    'iban' => 'CH4431999123000889012',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITH_REFERENCE->value,
                    'reference' => '123456789012345678901234567890',
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The reference must be between 1 and 26 digits.'
                ],
                'reference'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => 'EUR',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => Str::random(71),
                ],
                [
                    0 => 'The additional information must not be greater than 70 characters.'
                ],
                'additional_information'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => 'EUR',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => 'doc.txt',
                    'file_mode' => FileModeEnum::ADD->value
                ],
                [
                    0 => 'The file must be a file of type: pdf.'
                ],
                'file'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => 'EUR',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => '',
                    'file_mode' => 'wrongMode'
                ],
                [
                    0 => 'The selected file mode is invalid.'
                ],
                'file_mode'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => 'EUR',
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => ''
                ],
                [
                    0 => 'The file mode field is required when file is empty.'
                ],
                'file_mode'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => '',
                    'file_mode' => FileModeEnum::OVERLAY->value
                ],
                [
                    0 => 'The file overlay page field is required when file mode is overlay.'
                ],
                'file_overlay_page'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'PL',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => UploadedFile::fake()->createWithContent('pdfTest.pdf', file_get_contents('storage/test/test.pdf')),
                    'file_mode' => FileModeEnum::OVERLAY->value,
                    'file_overlay_page' => 123
                ],
                [
                    0 => 'Page to overlay does not exists.'
                ],
                'file_overlay_page'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The language field is required.'
                ],
                'language'
            ],
            [
                [
                    'iban' => 'CH5604835012345678009',
                    'creditor_name' => 'Test Name Creditor',
                    'creditor_street' => 'Test Street Creditor',
                    'creditor_street_number' => '70',
                    'creditor_post_code' => '8005',
                    'creditor_city' => 'Zürich',
                    'creditor_country' => 'CH',
                    'total_amount' => '100.12',
                    'currency' => CurrencyEnum::CHF->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => 'pl',
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'reference' => '12345678901234567890123456',
                    'additional_information' => 'TestPaymentId123'
                ],
                [
                    0 => 'The selected language is invalid.'
                ],
                'language'
            ],
        ];
    }
}
