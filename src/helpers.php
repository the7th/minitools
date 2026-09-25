<?php

declare(strict_types=1);

use App\Lang;
use App\View;

function view(string $name, array $data = []): string
{
    return View::render($name, $data);
}

function t(string $key, array $replace = []): string
{
    return Lang::get($key, $replace);
}

function lang(): string
{
    return Lang::locale();
}

function lang_choices(): array
{
    return Lang::LABELS;
}

function lang_url(string $locale): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    return $path . '?lang=' . $locale;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function human_size(int $bytes): string
{
    if ($bytes >= 1024 * 1024) {
        return number_format($bytes / (1024 * 1024), 1) . ' MB';
    }

    if ($bytes >= 1024) {
        return number_format($bytes / 1024, 0) . ' KB';
    }

    return $bytes . ' B';
}
