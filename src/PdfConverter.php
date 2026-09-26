<?php

declare(strict_types=1);

namespace App;

use RuntimeException;
use Symfony\Component\Process\Process;

class PdfConverter
{
    public const MAX_BYTES = 25 * 1024 * 1024;

    public const MAX_PAGES = 20;

    public const TIMEOUT = 60;

    public const DEFAULT_DPI = 150;

    public const DPI_CHOICES = [72, 150, 300];

    public const FORMATS = ['jpg', 'png'];

    public const JPEG_QUALITY = 88;

    public function __construct(private readonly ?string $binary = null)
    {
    }

    public function binary(): string
    {
        return $this->binary ?? (getenv('GS_BINARY') ?: 'gs');
    }

    public function pageCount(string $pdf): int
    {
        $process = $this->process($pdf, [
            '-sDEVICE=inkcov',
            '-sOutputFile=-',
            '-',
        ]);

        $lines = array_filter(array_map('trim', preg_split('/\R/u', $process->getOutput()) ?: []));

        if ($lines === []) {
            throw new RuntimeException(t('error.pdf_failed'));
        }

        return count($lines);
    }

    /**
     * @param  list<int>  $pages
     * @return array<int, string> page number => image bytes
     */
    public function render(string $pdf, array $pages, string $format, int $dpi): array
    {
        $images = [];

        foreach ($pages as $page) {
            $images[$page] = $this->renderPage($pdf, $page, $format, $dpi);
        }

        return $images;
    }

    private function renderPage(string $pdf, int $page, string $format, int $dpi): string
    {
        $process = $this->process($pdf, [
            '-sDEVICE=' . ($format === 'png' ? 'png16m' : 'jpeg'),
            '-r' . $dpi,
            '-dFirstPage=' . $page,
            '-dLastPage=' . $page,
            '-dTextAlphaBits=4',
            '-dGraphicsAlphaBits=4',
            '-dUseCropBox',
            ...($format === 'png' ? [] : ['-dJPEGQ=' . self::JPEG_QUALITY]),
            '-sOutputFile=-',
            '-',
        ]);

        $image = $process->getOutput();

        if ($image === '') {
            throw new RuntimeException(t('error.pdf_failed'));
        }

        return $image;
    }

    private function process(string $pdf, array $arguments): Process
    {
        $process = new Process([
            $this->binary(),
            '-q',
            '-dNOPAUSE',
            '-dBATCH',
            ...$arguments,
        ]);

        $process->setInput($pdf);
        $process->setTimeout(self::TIMEOUT);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput()) ?: t('error.pdf_failed')
            );
        }

        return $process;
    }
}
