<?php

declare(strict_types=1);

namespace App;

use Imagick;
use ImagickException;
use RuntimeException;

class PngCompressor
{
    public const MAX_BYTES = 25 * 1024 * 1024;

    public const MAX_COLORS = 256;

    public const MAX_RMSE = 0.05;

    public const LOSSY_GAIN = 0.9;

    public function compress(string $png): string
    {
        if (! extension_loaded('imagick')) {
            throw new RuntimeException('Server ni tak ada extension Imagick, jadi PNG tak boleh diproses.');
        }

        if (str_contains($png, 'acTL')) {
            return $png;
        }

        $source = new Imagick();
        $source->readImageBlob($png);
        $source->setImageColorspace(Imagick::COLORSPACE_SRGB);

        $lossless = $this->lossless($source);
        $lossy = $this->lossy($source);
        $source->clear();

        if ($lossy !== null && strlen($lossy) < strlen($lossless) * self::LOSSY_GAIN) {
            return $lossy;
        }

        return $lossless;
    }

    private function lossless(Imagick $source): string
    {
        $image = clone $source;
        $image->setImageFormat('png');
        $image->stripImage();

        return $this->encode($image);
    }

    private function lossy(Imagick $source): ?string
    {
        try {
            $image = clone $source;
            $image->setImageFormat('png');
            $image->quantizeImage(self::MAX_COLORS, Imagick::COLORSPACE_SRGB, 0, true, false);
            $image->setImageDepth(8);
            $image->stripImage();

            $encoded = $this->encode($image);
            $image->clear();

            return $this->acceptable($source, $encoded) ? $encoded : null;
        } catch (ImagickException) {
            return null;
        }
    }

    private function acceptable(Imagick $source, string $candidate): bool
    {
        $image = new Imagick();
        $image->readImageBlob($candidate);

        try {
            $rmse = $source->compareImages($image, Imagick::METRIC_ROOTMEANSQUAREDERROR)[1];
        } finally {
            $image->clear();
        }

        return $rmse <= self::MAX_RMSE;
    }

    private function encode(Imagick $image): string
    {
        $image->setOption('png:compression-level', '9');
        $image->setOption('png:compression-filter', '5');
        $image->setOption('png:compression-strategy', '1');

        $encoded = $image->getImageBlob();
        $image->clear();

        return $encoded;
    }
}
