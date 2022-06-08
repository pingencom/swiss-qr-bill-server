<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\Enum\CurrencyEnum;
use App\Support\Enum\LanguageEnum;
use App\Support\Enum\FileModeEnum;
use App\Support\Enum\QRTypeEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class QrCodeGenerateFromUITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->travelTo(Carbon::make('2012-01-01'));
    }

    /**
     * @dataProvider positiveCaseProvider
     */
    public function testPositive(array $body, string $expectedFileName): void
    {
        $response = $this->post('sendRequest', $body);

        $response->assertDownload($expectedFileName);
    }

    protected function positiveCaseProvider(): array
    {
        $this->travelTo(Carbon::make('2012-01-01'));

        return [
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
                    'language' => LanguageEnum::DE->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => null,
                    'file_mode' => FileModeEnum::ADD->value,
                    'file_overlay_page' => null
                ],
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
                    'currency' => CurrencyEnum::EUR->value,
                    'debitor_name' => 'Test Name Debitor',
                    'debitor_street' => 'Test Street Debitor',
                    'debitor_street_number' => '70',
                    'debitor_post_code' => '8005',
                    'debitor_city' => 'Zürich',
                    'debitor_country' => 'CH',
                    'language' => LanguageEnum::EN->value,
                    'type' => QRTypeEnum::WITHOUT_REFERENCE->value,
                    'additional_information' => 'TestPaymentId123',
                    'file' => UploadedFile::fake()->createWithContent('pdfTest.pdf', file_get_contents('storage/test/test.pdf')),
                    'file_mode' => 'add',
                    'which-page' => null
                ],
                'QR-Bill_CH5604835012345678009_' . now()->format('Y-m-d_H:i:s') . '.pdf'
            ]
        ];
    }
}
