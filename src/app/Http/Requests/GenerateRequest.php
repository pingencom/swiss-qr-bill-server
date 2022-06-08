<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Enum\CurrencyEnum;
use App\Support\Enum\LanguageEnum;
use App\Support\Enum\FileModeEnum;
use App\Support\Enum\QRTypeEnum;
use App\Support\Validation\CountryCode;
use App\Support\Validation\Iban;
use App\Support\Validation\PageNumber;
use App\Support\Validation\QRIban;
use Illuminate\Foundation\Http\FormRequest;

class GenerateRequest extends FormRequest implements GenerateRequestInterface
{
    public function rules(): array
    {
        $pdfPath = '';

        if($this->file()) {
            $pdfPath = $this->file('file')->getPath() . '/' . $this->file('file')->getFilename();
        }

        return [
            'iban' => ['required', 'size:21', new Iban, new QRIban($this->get('type'))],
            'creditor_name' => 'required|max:70',
            'creditor_street' => 'required|max:70',
            'creditor_street_number' => 'required|max:70',
            'creditor_post_code' => 'required|max:16',
            'creditor_city' => 'required|max:35',
            'creditor_country' => ['required', 'max:2', new CountryCode],
            'total_amount' => ['required', 'max:70', 'regex:/^\d+(\.\d{1,2})?$/'],
            'currency' => 'required|max:3|in:' . implode(',', array_column(CurrencyEnum::cases(), 'value')),
            'debitor_name' => 'required|max:70',
            'debitor_street' => 'required|max:70',
            'debitor_street_number' => 'required|max:70',
            'debitor_post_code' => 'required|max:16',
            'debitor_city' => 'required|max:35',
            'debitor_country' => ['required', 'max:2', new CountryCode],
            'type' => 'required|in:' . implode(',', array_column(QRTypeEnum::cases(), 'value')),
            'reference' => 'required_if:type,=,QRR|digits_between:1,26|nullable',
            'additional_information' => 'max:70',
            'file' => 'nullable|mimes:pdf',
            'file_mode' => 'required_if:file,!=,null|nullable|in:' . implode(',', array_column(FileModeEnum::cases(), 'value')),
            'file_overlay_page' => ['required_if:file_mode,=,overlay', 'int', 'nullable', new PageNumber($pdfPath)],
            'language' => 'required|in:' . implode(',', array_column(LanguageEnum::cases(), 'value'))
        ];
    }
}
//must not be greater than 70 characters.
