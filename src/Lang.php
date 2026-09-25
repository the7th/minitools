<?php

declare(strict_types=1);

namespace App;

class Lang
{
    public const DEFAULT = 'ms';

    public const SUPPORTED = ['ms', 'en'];

    public const LABELS = ['ms' => 'BM', 'en' => 'EN'];

    private static string $locale = self::DEFAULT;

    private static array $cache = [];

    public static function set(?string $locale): void
    {
        if ($locale !== null && in_array($locale, self::SUPPORTED, true)) {
            self::$locale = $locale;
        }
    }

    public static function locale(): string
    {
        return self::$locale;
    }

    public static function get(string $key, array $replace = []): string
    {
        $lines = self::$cache[self::$locale]
            ??= require dirname(__DIR__) . '/lang/' . self::$locale . '.php';

        $line = $lines[$key] ?? $key;

        foreach ($replace as $search => $value) {
            $line = str_replace(':' . $search, (string) $value, $line);
        }

        return $line;
    }
}
