<?php

declare(strict_types=1);

namespace App;

class Zip
{
    /**
     * Build a ZIP archive in memory. Entries are stored, not compressed,
     * which is fine for images that are already compressed.
     *
     * @param  array<string, string>  $entries  filename => contents
     */
    public static function create(array $entries): string
    {
        $now = getdate();
        $time = ($now['hours'] << 11) | ($now['minutes'] << 5) | ($now['seconds'] >> 1);
        $date = (($now['year'] - 1980) << 9) | ($now['mon'] << 5) | $now['mday'];

        $files = '';
        $directory = '';

        foreach ($entries as $name => $contents) {
            $name = (string) $name;
            $crc = crc32($contents);
            $size = strlen($contents);
            $offset = strlen($files);

            $files .= pack(
                'VvvvvvVVVvv',
                0x04034b50,
                20,
                0,
                0,
                $time,
                $date,
                $crc,
                $size,
                $size,
                strlen($name),
                0
            ) . $name . $contents;

            $directory .= pack(
                'VvvvvvvVVVvvvvvVV',
                0x02014b50,
                20,
                20,
                0,
                0,
                $time,
                $date,
                $crc,
                $size,
                $size,
                strlen($name),
                0,
                0,
                0,
                0,
                0,
                $offset
            ) . $name;
        }

        return $files . $directory . pack(
            'VvvvvVVv',
            0x06054b50,
            0,
            0,
            count($entries),
            count($entries),
            strlen($directory),
            strlen($files),
            0
        );
    }
}
