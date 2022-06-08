<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\DataTransferObjects\QrBillDataDTO;
use App\Support\Enum\FileModeEnum;
use App\Support\Enum\QRTypeEnum;
use App\Support\FileHelper;
use App\Support\NumberFormatter;
use App\Support\PdfHelper;
use App\Support\QrCodeHelper;
use App\Support\QrReferenceHelper;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Process;

class QRBillService
{
    public function __construct(
        public QrCodeHelper $qrCodeHelper,
        public QrReferenceHelper $qrReferenceHelper,
        public FileHelper $fileHelpers,
        public NumberFormatter $numberFormatter
    )
    {
    }

    public function generate(QrBillDataDTO $qrBillDataDTO): StreamedResponse
    {
        $outputPDF = $this->fileHelpers->tempnam('/tmp') . '.pdf';
        $rawPdfContent = $this->generateQrBillPdf($this->getQrBillPdfHtmlContent($qrBillDataDTO));

        file_put_contents($outputPDF, $rawPdfContent);

        if ($qrBillDataDTO->file_path) {
            if ($qrBillDataDTO->file_mode->value === FileModeEnum::OVERLAY->value) {
                $this->overlayPdf($qrBillDataDTO, $outputPDF);
            } else {
                $this->addPageToPdf($rawPdfContent, $qrBillDataDTO->file_path, $outputPDF);
            }
        }

        $outputContent = file_get_contents($outputPDF);
        $this->fileHelpers->destroyFile($outputPDF);

        return response()->streamDownload(function () use($outputContent) {
            echo $outputContent; //@codeCoverageIgnore
        }, 'QR-Bill_' . $qrBillDataDTO->iban . '_' . now()->format('Y-m-d_H:i:s') . '.pdf');
    }

    private function getQrBillPdfHtmlContent(QrBillDataDTO $qrBillDataDTO): string
    {
        App::setLocale($qrBillDataDTO->language->value);

        if ($qrBillDataDTO->reference) {
            $qrReference = $this->qrReferenceHelper->formatQrReference(
                $this->qrReferenceHelper->generateQrReference($qrBillDataDTO->reference)
            );
        } else {
            $qrReference = '';
        }

        return view(
            'qrcode',
            [
                'amount' => $this->numberFormatter->format($qrBillDataDTO->total_amount),
                'qrCode' => $this->qrCodeHelper->generateQrCode($qrBillDataDTO, $qrReference),
                'qrReference' => $qrReference,
                'data' => $qrBillDataDTO,
                'qrr' => QRTypeEnum::WITH_REFERENCE->value,
                'non' => QRTypeEnum::WITHOUT_REFERENCE->value
            ]
        )->render();
    }

    private function generateQrBillPdf(string $qrBillHTMLContent): string
    {
        $dompdf = new Dompdf();
        $dompdf->getOptions()->setChroot(resource_path());
        $dompdf->getOptions()->setIsPhpEnabled(true);
        $dompdf->loadHtml($qrBillHTMLContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }

    private function overlayPdf(QrBillDataDTO $qrBillDataDTO, string $outputPDF): void
    {
        /** @var PdfHelper $pdfHelper */
        $pdfHelper = app()->make(PdfHelper::class);
        $pdfToMerge = $qrBillDataDTO->file_path;

        $pageNumber = $qrBillDataDTO->file_overlay_page;
        $totalPages = $pdfHelper->countPdfPages($pdfToMerge);

        $tmpSinglePageToStamp = $this->fileHelpers->tempnam('/tmp') . '.pdf';
        Process::fromShellCommandline(
            'pdftk ' . $pdfToMerge
            . ' cat ' . $pageNumber
            . ' output ' . $tmpSinglePageToStamp
        )->mustRun();

        $tmpSinglePageStamped = $this->fileHelpers->tempnam('/tmp') . '.pdf';
        Process::fromShellCommandline(
            'pdftk ' . $tmpSinglePageToStamp
            . ' stamp ' . $outputPDF
            . ' output ' . $tmpSinglePageStamped
        )->mustRun();

        $commandStart = 'pdftk A=' . $pdfToMerge . ' B=' . $tmpSinglePageStamped;
        $commandEnd = ' output ' . $outputPDF;

        Process::fromShellCommandline(
            $commandStart . $this->getMergeOption($totalPages, $pageNumber) . $commandEnd
        )->mustRun();

        $this->fileHelpers->destroyFile($tmpSinglePageToStamp);
        $this->fileHelpers->destroyFile($tmpSinglePageStamped);
    }

    private function getMergeOption(int $totalPages, int $pageNumber): string
    {
        if ($totalPages === 1) {
            $commandOption = ' cat B';
        } else if ($pageNumber === 1) {
            $commandOption = ' cat B A' . $pageNumber + 1 . '-end';
        } else if ($pageNumber === 2 && $pageNumber != $totalPages) {
            $commandOption = ' cat A1' . ' B A' . $pageNumber + 1 . '-end';
        } else if ($pageNumber === 2 && $pageNumber === $totalPages) {
            $commandOption = ' cat A1' . ' B';
        } else if ($pageNumber === $totalPages) {
            $commandOption = ' cat A1-' . $pageNumber - 1 . ' B';
        } else {
            $commandOption = ' cat A1-' . $pageNumber - 1 . ' B A' . $pageNumber + 1 . '-end';
        }

        return $commandOption;
    }

    private function addPageToPdf(string $qrContent, string $pdfToMerge, string $outputPDF): void
    {
        $tmpPdfWithQr = $this->fileHelpers->tempnam('/tmp') . '.pdf';
        file_put_contents($tmpPdfWithQr, $qrContent);

        Process::fromShellCommandline(
            'pdftk ' . $pdfToMerge
            . ' ' . $tmpPdfWithQr . ' cat output ' . $outputPDF
        )->mustRun();

        $this->fileHelpers->destroyFile($tmpPdfWithQr);
    }
}
