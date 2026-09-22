<?php

declare(strict_types=1);

namespace App;

use RuntimeException;
use Symfony\Component\Process\Process;

class Compressor
{
    public const MAX_BYTES = 25 * 1024 * 1024;

    public const PRESET = 'ebook';

    public const TIMEOUT = 120;

    public function __construct(private readonly ?string $binary = null)
    {
    }

    public function binary(): string
    {
        return $this->binary ?? (getenv('GS_BINARY') ?: 'gs');
    }

    public function compress(string $pdf): string
    {
        $process = new Process([
            $this->binary(),
            '-sDEVICE=pdfwrite',
            '-dCompatibilityLevel=1.5',
            '-dPDFSETTINGS=/' . self::PRESET,
            '-dNOPAUSE',
            '-dQUIET',
            '-dBATCH',
            '-dDetectDuplicateImages=true',
            '-dCompressFonts=true',
            '-dSubsetFonts=true',
            '-dAutoRotatePages=/None',
            '-sOutputFile=-',
            '-',
        ]);

        $process->setInput($pdf);
        $process->setTimeout(self::TIMEOUT);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput()) ?: 'Ghostscript gagal compress PDF ini.'
            );
        }

        return $process->getOutput();
    }
}
