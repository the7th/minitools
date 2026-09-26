<?php

declare(strict_types=1);

use App\Compressor;
use App\Lang;
use App\PdfConverter;
use App\PngCompressor;
use App\QrGenerator;
use App\ResumeChecker;
use App\Zip;

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

if ($method === 'GET' && $path === '/projek/sistem-kurier') {
    echo view('project', ['title' => t('meta.kurier'), 'project' => 'sistem-kurier']);

    exit;
}

if ($method === 'GET' && $path === '/projek/sdms') {
    echo view('project', ['title' => t('meta.sdms'), 'project' => 'sdms']);

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

if ($method === 'GET' && $path === '/tools/pdf-to-image') {
    echo view('tools/pdf-to-image', ['title' => t('meta.pdf_to_image')]);

    exit;
}

if ($method === 'POST' && $path === '/tools/pdf-to-image') {
    try {
        echo handle_pdf_to_image();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('tools/pdf-to-image', [
            'title' => t('meta.pdf_to_image'),
            'error' => $e->getMessage(),
            'format' => (string) ($_POST['format'] ?? 'jpg'),
            'dpi' => (int) ($_POST['dpi'] ?? PdfConverter::DEFAULT_DPI),
            'pages' => (string) ($_POST['pages'] ?? ''),
        ]);
    }

    exit;
}

if ($method === 'GET' && $path === '/tools/qr-code') {
    echo view('tools/qr-code', ['title' => t('meta.qr')]);

    exit;
}

if ($method === 'POST' && $path === '/tools/qr-code') {
    try {
        echo handle_qr_code();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('tools/qr-code', [
            'title' => t('meta.qr'),
            'error' => $e->getMessage(),
            'content' => (string) ($_POST['content'] ?? ''),
            'level' => (string) ($_POST['level'] ?? QrGenerator::DEFAULT_LEVEL),
            'size' => (int) ($_POST['size'] ?? QrGenerator::DEFAULT_SIZE),
            'format' => (string) ($_POST['format'] ?? 'png'),
        ]);
    }

    exit;
}

if ($method === 'GET' && $path === '/tools/ats-checker') {
    echo view('tools/ats-checker', ['title' => t('meta.ats')]);

    exit;
}

if ($method === 'POST' && $path === '/tools/ats-checker') {
    try {
        echo handle_ats_checker();
    } catch (Throwable $e) {
        http_response_code(422);
        echo view('tools/ats-checker', [
            'title' => t('meta.ats'),
            'error' => $e->getMessage(),
            'job' => (string) ($_POST['job'] ?? ''),
        ]);
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

function handle_ats_checker(): string
{
    [$original] = read_upload('resume', ResumeChecker::MAX_BYTES, 'PDF');

    if (! str_starts_with($original, '%PDF-')) {
        throw new RuntimeException(t('error.invalid_pdf'));
    }

    $job = trim((string) ($_POST['job'] ?? ''));

    return view('ats-result', [
        'title' => t('meta.ats'),
        'report' => (new ResumeChecker())->analyse($original, $job),
    ]);
}

function handle_pdf_to_image(): string
{
    [$original, $clientName] = read_upload('pdf', PdfConverter::MAX_BYTES, 'PDF');

    if (! str_starts_with($original, '%PDF-')) {
        throw new RuntimeException(t('error.invalid_pdf'));
    }

    $format = (string) ($_POST['format'] ?? 'jpg');

    if (! in_array($format, PdfConverter::FORMATS, true)) {
        throw new RuntimeException(t('error.invalid_format'));
    }

    $dpi = (int) ($_POST['dpi'] ?? PdfConverter::DEFAULT_DPI);

    if (! in_array($dpi, PdfConverter::DPI_CHOICES, true)) {
        throw new RuntimeException(t('error.invalid_dpi'));
    }

    $converter = new PdfConverter();
    $pages = parse_page_selection((string) ($_POST['pages'] ?? ''), $converter->pageCount($original));

    set_time_limit(PdfConverter::TIMEOUT * count($pages) + 30);

    $images = $converter->render($original, $pages, $format, $dpi);
    $stem = safe_name($clientName, t('download.document'));
    $extension = $format === 'png' ? 'png' : 'jpg';
    $mime = $format === 'png' ? 'image/png' : 'image/jpeg';

    $result = [];
    $archive = [];

    foreach ($images as $page => $bytes) {
        $name = $stem . '-page-' . $page . '.' . $extension;

        $result[] = [
            'page' => $page,
            'name' => $name,
            'mime' => $mime,
            'base64' => base64_encode($bytes),
        ];

        $archive[$name] = $bytes;
    }

    return view('pdf-images-result', [
        'title' => t('meta.pdf_to_image'),
        'format' => strtoupper($extension),
        'images' => $result,
        'size' => array_sum(array_map('strlen', $images)),
        'zipName' => $stem . '-images.zip',
        'zipBase64' => base64_encode(Zip::create($archive)),
        'toolPath' => '/tools/pdf-to-image',
    ]);
}

function handle_qr_code(): string
{
    $content = trim((string) ($_POST['content'] ?? ''));

    if ($content === '') {
        throw new RuntimeException(t('error.empty_content'));
    }

    if (mb_strlen($content) > QrGenerator::MAX_CHARS) {
        throw new RuntimeException(t('error.content_too_long', ['max' => QrGenerator::MAX_CHARS]));
    }

    $level = strtoupper((string) ($_POST['level'] ?? QrGenerator::DEFAULT_LEVEL));

    if (! in_array($level, QrGenerator::LEVELS, true)) {
        throw new RuntimeException(t('error.invalid_level'));
    }

    $size = (int) ($_POST['size'] ?? QrGenerator::DEFAULT_SIZE);

    if (! in_array($size, QrGenerator::SIZES, true)) {
        throw new RuntimeException(t('error.invalid_size'));
    }

    $format = (string) ($_POST['format'] ?? 'png');

    if (! in_array($format, QrGenerator::FORMATS, true)) {
        throw new RuntimeException(t('error.invalid_format'));
    }

    $image = (new QrGenerator())->render($content, $format, $level, $size);

    return view('qr-result', [
        'title' => t('meta.qr'),
        'level' => $level,
        'format' => strtoupper($format),
        'mime' => $format === 'svg' ? 'image/svg+xml' : 'image/png',
        'base64' => base64_encode($image),
        'downloadName' => 'qrcode.' . $format,
        'size' => strlen($image),
        'toolPath' => '/tools/qr-code',
    ]);
}

function parse_page_selection(string $selection, int $total): array
{
    $selection = trim($selection);

    if ($selection === '') {
        $pages = range(1, $total);
    } else {
        $pages = [];

        foreach (explode(',', $selection) as $part) {
            $part = trim($part);

            if (preg_match('/^(\d+)\s*-\s*(\d+)$/', $part, $matches)) {
                $first = (int) $matches[1];
                $last = (int) $matches[2];
            } elseif (ctype_digit($part)) {
                $first = $last = (int) $part;
            } else {
                throw new RuntimeException(t('error.invalid_pages'));
            }

            if ($first < 1 || $last < $first) {
                throw new RuntimeException(t('error.invalid_pages'));
            }

            if ($last > $total) {
                throw new RuntimeException(t('error.page_range', ['total' => $total]));
            }

            $pages = [...$pages, ...range($first, $last)];
        }

        $pages = array_values(array_unique($pages));
        sort($pages);
    }

    if (count($pages) > PdfConverter::MAX_PAGES) {
        throw new RuntimeException(t('error.too_many_pages', ['max' => PdfConverter::MAX_PAGES]));
    }

    return $pages;
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
    return safe_name($clientName, $fallback) . '-compressed.' . $extension;
}

function safe_name(string $clientName, string $fallback): string
{
    $name = pathinfo($clientName, PATHINFO_FILENAME);
    $name = trim((string) preg_replace('/[^A-Za-z0-9 _.-]+/', '', $name));

    return $name !== '' ? $name : $fallback;
}
