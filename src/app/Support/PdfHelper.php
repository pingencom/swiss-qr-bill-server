<?php

declare(strict_types=1);

namespace App\Support;

use Symfony\Component\Process\Process;

class PdfHelper
{
    public function countPdfPages(string $pdf): int
    {
        $command = "pdfinfo $pdf | grep Pages | awk '{print $2}'";

        $process = Process::fromShellCommandline($command);
        $process->mustRun();

        return (int)$process->getOutput();
    }
}
