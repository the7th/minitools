<?php

declare(strict_types=1);

use App\Compressor;
use App\Lang;
use App\PngCompressor;

require __DIR__ . '/../vendor/autoload.php';

ini_set('memory_limit', '512M');
set_time_limit(Compressor::TIMEOUT + 30);

$requested = $_GET['lang'] ?? null;

if (is_string($requested) && in_array($requested, Lang::SUPPORTED, true)) {
    setcookie('lang', $requested, [
        'expires' => time() + 60 * 60 * 24 * 365,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    Lang::set($requested);
} else {
    Lang::set(is_string($_COOKIE['lang'] ?? null) ? $_COOKIE['lang'] : null);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($method === 'GET' && $path === '/') {
    echo view('home', ['title' => t('meta.home')]);

    exit;
}

if ($method === 'GET' && $path === '/tentang-aku') {
    echo view('about', ['title' => t('meta.about')]);

    exit;
}

if ($method === 'GET' && $path === '/tools') {
    echo view('tools', ['title' => t('meta.tools')]);

    exit;
}

if ($method === 'GET' && $path === '/tools/compress-pdf') {
    echo view('tools/compress-pdf', ['title' => t('meta.compress_pdf')]);

    exit;
}

if ($method === 'POST' && $path === '/tools/compress-pdf') {
    try {
        echo handle_compress_pdf();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('tools/compress-pdf', ['title' => t('meta.compress_pdf'), 'error' => $e->getMessage()]);
    }

    exit;
}

if ($method === 'GET' && $path === '/tools/compress-png') {
    echo view('tools/compress-png', ['title' => t('meta.compress_png')]);

    exit;
}

if ($method === 'POST' && $path === '/tools/compress-png') {
    try {
        echo handle_compress_png();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('tools/compress-png', ['title' => t('meta.compress_png'), 'error' => $e->getMessage()]);
    }

    exit;
}

http_response_code(404);
echo view('error', ['title' => t('meta.not_found'), 'error' => t('error.not_found')]);
exit;

function handle_compress_pdf(): string
{
    [$original, $clientName] = read_upload('pdf', Compressor::MAX_BYTES, 'PDF');

    if (! str_starts_with($original, '%PDF-')) {
        throw new RuntimeException(t('error.invalid_pdf'));
    }

    $compressed = (new Compressor())->compress($original);
    $improved = $compressed !== '' && strlen($compressed) < strlen($original);

    if (! $improved) {
        $compressed = $original;
    }

    return view('result', [
        'title' => t('meta.compress_pdf'),
        'tool' => 'PDF',
        'mime' => 'application/pdf',
        'preview' => 'pdf',
        'toolPath' => '/tools/compress-pdf',
        'base64' => base64_encode($compressed),
        'downloadName' => download_name($clientName, t('download.document'), 'pdf'),
        'originalSize' => strlen($original),
        'compressedSize' => strlen($compressed),
        'improved' => $improved,
    ]);
}

function handle_compress_png(): string
{
    [$original, $clientName] = read_upload('png', PngCompressor::MAX_BYTES, 'PNG');

    if (! str_starts_with($original, "\x89PNG\r\n\x1a\n")) {
        throw new RuntimeException(t('error.invalid_png'));
    }

    $compressed = (new PngCompressor())->compress($original);
    $improved = $compressed !== '' && strlen($compressed) < strlen($original);

    if (! $improved) {
        $compressed = $original;
    }

    return view('result', [
        'title' => t('meta.compress_png'),
        'tool' => 'PNG',
        'mime' => 'image/png',
        'preview' => 'image',
        'toolPath' => '/tools/compress-png',
        'base64' => base64_encode($compressed),
        'downloadName' => download_name($clientName, t('download.image'), 'png'),
        'originalSize' => strlen($original),
        'compressedSize' => strlen($compressed),
        'improved' => $improved,
    ]);
}

function read_upload(string $field, int $maxBytes, string $label): array
{
    $file = $_FILES[$field] ?? null;

    if ($file === null && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        throw new RuntimeException(t('error.too_large', ['size' => human_size($maxBytes)]));
    }

    if (! is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException(t('error.choose_file', ['label' => $label]));
    }

    if (in_array($file['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
        throw new RuntimeException(t('error.too_large', ['size' => human_size($maxBytes)]));
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException(t('error.upload_failed', ['code' => $file['error']]));
    }

    if (($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException(t('error.too_large', ['size' => human_size($maxBytes)]));
    }

    if (! is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException(t('error.invalid_upload'));
    }

    $bytes = (string) file_get_contents($file['tmp_name']);
    @unlink($file['tmp_name']);

    return [$bytes, (string) $file['name']];
}

function download_name(string $clientName, string $fallback, string $extension): string
{
    $name = pathinfo($clientName, PATHINFO_FILENAME);
    $name = trim((string) preg_replace('/[^A-Za-z0-9 _.-]+/', '', $name)) ?: $fallback;

    return $name . '-compressed.' . $extension;
}
