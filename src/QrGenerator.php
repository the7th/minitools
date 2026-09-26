<?php

declare(strict_types=1);

namespace App;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Encoder\QrCode;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use RuntimeException;
use Throwable;

class QrGenerator
{
    public const MAX_CHARS = 1000;

    public const LEVELS = ['L', 'M', 'Q', 'H'];

    public const DEFAULT_LEVEL = 'M';

    public const SIZES = [200, 300, 512, 1000];

    public const DEFAULT_SIZE = 300;

    public const FORMATS = ['png', 'svg'];

    private const MARGIN = 4;

    public function render(string $content, string $format, string $level, int $size): string
    {
        try {
            $qrCode = Encoder::encode($content, $this->level($level), 'UTF-8');
        } catch (Throwable $e) {
            throw new RuntimeException(t('error.qr_failed'), 0, $e);
        }

        return $format === 'svg' ? $this->svg($qrCode, $size) : $this->png($qrCode, $size);
    }

    private function png(QrCode $qrCode, int $size): string
    {
        if (! extension_loaded('imagick')) {
            throw new RuntimeException('Server ni tak ada extension Imagick, jadi QR PNG tak boleh dijana.');
        }

        $matrix = $qrCode->getMatrix();
        $width = $matrix->getWidth();
        $total = $width + self::MARGIN * 2;
        $scale = max(1, (int) round($size / $total));
        $imageSize = $total * $scale;

        $image = new Imagick();
        $image->newImage($imageSize, $imageSize, new ImagickPixel('white'));
        $image->setImageFormat('png');
        $image->setOption('png:compression-level', '9');
        $image->setOption('png:compression-filter', '5');

        $draw = new ImagickDraw();
        $draw->setFillColor(new ImagickPixel('black'));

        for ($y = 0; $y < $width; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($matrix->get($x, $y)) {
                    $draw->rectangle(
                        (float) (($x + self::MARGIN) * $scale),
                        (float) (($y + self::MARGIN) * $scale),
                        (float) (($x + self::MARGIN + 1) * $scale - 1),
                        (float) (($y + self::MARGIN + 1) * $scale - 1)
                    );
                }
            }
        }

        $image->drawImage($draw);
        $image->setImageType(Imagick::IMGTYPE_BILEVEL);

        $blob = $image->getImageBlob();
        $image->clear();
        $draw->clear();

        return $blob;
    }

    private function svg(QrCode $qrCode, int $size): string
    {
        $renderer = new ImageRenderer(new RendererStyle($size, self::MARGIN), new SvgImageBackEnd());

        return $renderer->render($qrCode);
    }

    private function level(string $level): ErrorCorrectionLevel
    {
        return match ($level) {
            'L' => ErrorCorrectionLevel::L(),
            'Q' => ErrorCorrectionLevel::Q(),
            'H' => ErrorCorrectionLevel::H(),
            default => ErrorCorrectionLevel::M(),
        };
    }
}
