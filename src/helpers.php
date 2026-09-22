<?php

declare(strict_types=1);

use App\View;

function view(string $name, array $data = []): string
{
    return View::render($name, $data);
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
