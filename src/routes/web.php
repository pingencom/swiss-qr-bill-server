<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;
use App\Support\Enum\CurrencyEnum;
use App\Support\Enum\LanguageEnum;
use App\Support\Enum\FileModeEnum;
use App\Support\Enum\QRTypeEnum;
use Illuminate\Support\Facades\Route;
use League\ISO3166\ISO3166;

Route::get('/', function () {
    return view('index', [
        'countries' => (new ISO3166)->all(),
        'currencies' => CurrencyEnum::cases(),
        'defaultCurrency' => CurrencyEnum::CHF,
        'languages' => LanguageEnum::cases(),
        'defaultLanguage' => LanguageEnum::EN,
        'types' => QRTypeEnum::cases(),
        'defaultType' => QRTypeEnum::WITH_REFERENCE,
        'fileModes' => FileModeEnum::cases(),
        'defaultFileMode' => FileModeEnum::ADD
    ]);
});

Route::post(
    '/sendRequest',
    [Controller::class, 'sendRequest']
)->name('sendRequest');
