<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertPdfTextEquals(string $expectedTextFile, string $pdfContent): void
    {
        $pdfPath = 'tmpTest.pdf';
        Storage::put($pdfPath, $pdfContent);

        $process = new Process([
            'pdftotext',
            stream_get_meta_data(Storage::readStream($pdfPath))['uri'],
            '-'
        ]);

        $process->mustRun();
        //file_put_contents($expectedTextFile, $process->getOutput());
        $this->assertEquals(file_get_contents($expectedTextFile), $process->getOutput());
        Storage::delete($pdfPath);
    }
}
