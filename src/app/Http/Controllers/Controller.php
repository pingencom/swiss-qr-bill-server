<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateRequest;
use App\Http\Requests\GenerateRequestApi;
use App\Http\Requests\GenerateRequestInterface;
use App\Services\QRBillService;
use App\Support\DataTransferObjects\QrBillDataDTO;
use App\Support\Enum\CurrencyEnum;
use App\Support\Enum\LanguageEnum;
use App\Support\Enum\FileModeEnum;
use App\Support\Enum\QRTypeEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function generate(GenerateRequestApi $request, QRBillService $generateService): StreamedResponse {
        return $generateService->generate($this->getDTO($request));
    }

    public function sendRequest(GenerateRequest $request, QRBillService $generateService): StreamedResponse {
        if($request->file === null) {
            $request->request->remove('file');
            $request->request->remove('file_mode');
            $request->request->remove('file_overlay_page');
        }

        if($request->get('file_overlay_page') === null || $request->get('file_mode') === 'add') {
            $request->request->remove('file_overlay_page');
        }

        return $generateService->generate($this->getDTO($request));
    }

    private function getDTO(GenerateRequestInterface $request): QrBillDataDTO
    {
        if ($request->has('file')) {
            $filePath = $request->file('file')->getPath() . '/' . $request->file('file')->getFilename();
        }else {
            $filePath = null;
        }

        return new QrBillDataDTO([
            'creditor_name' => $request->get('creditor_name'),
            'creditor_street' => $request->get('creditor_street'),
            'creditor_street_number' => $request->get('creditor_street_number'),
            'creditor_post_code' => $request->get('creditor_post_code'),
            'creditor_city' => $request->get('creditor_city'),
            'creditor_country' => $request->get('creditor_country'),
            'debitor_name' => $request->get('debitor_name'),
            'debitor_street' => $request->get('debitor_street'),
            'debitor_street_number' => $request->get('debitor_street_number'),
            'debitor_post_code' => $request->get('debitor_post_code'),
            'debitor_city' => $request->get('debitor_city'),
            'debitor_country' => $request->get('debitor_country'),
            'iban' => $request->get('iban'),
            'total_amount' => $request->get('total_amount'),
            'currency' => CurrencyEnum::from($request->get('currency')),
            'language' => LanguageEnum::from($request->get('language')),
            'additional_information' => $request->get('additional_information'),
            'type' => QRTypeEnum::from($request->get('type')),
            'reference' => $request->get('reference') ?? null,
            'file_path' => $filePath,
            'file_mode' =>  FileModeEnum::tryFrom($request->get('file_mode')),
            'file_overlay_page' => $request->get('file_overlay_page') ?? null
        ]);
    }
}
